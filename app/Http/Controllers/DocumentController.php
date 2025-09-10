<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['user', 'approver', 'rejecter'])->orderBy('created_at', 'desc')->get();
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240', // รองรับ PDF และไฟล์อื่นๆ
        ]);

        $document = new Document();
        $document->title = $request->title;
        $document->content = $request->content;
        $document->user_id = auth()->id();
        $document->status = 'pending'; // เริ่มต้นเป็น pending

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $filename, 'public');
            
            $document->file_path = $filePath;
            $document->original_filename = $file->getClientOriginalName();
        }

        $document->save();

        return redirect()->route('documents.index')->with('success', 'เอกสารถูกสร้างเรียบร้อยแล้ว และรอการอนุมัติ');
    }

    public function show(Document $document)
    {
        return view('documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240', // รองรับ PDF และไฟล์อื่นๆ
        ]);

        $document->title = $request->title;
        $document->content = $request->content;

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $filename, 'public');
            
            $document->file_path = $filePath;
            $document->original_filename = $file->getClientOriginalName();
        }

        $document->save();

        return redirect()->route('documents.index')->with('success', 'เอกสารถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function destroy(Document $document)
    {
        // Delete file if exists
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'เอกสารถูกลบเรียบร้อยแล้ว');
    }

    public function download(Document $document)
    {
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->download($document->file_path, $document->original_filename);
        }

        return back()->with('error', 'ไม่พบไฟล์');
    }

    // ฟังก์ชันการอนุมัติเอกสาร
    public function approve(Document $document)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $document->status = 'approved';
        $document->approved_by = auth()->id();
        $document->approved_at = now();
        $document->rejection_reason = null;
        $document->rejected_by = null;
        $document->rejected_at = null;
        $document->save();

        return redirect()->back()->with('success', 'เอกสารถูกอนุมัติเรียบร้อยแล้ว');
    }

    // ฟังก์ชันไม่อนุมัติเอกสาร
    public function reject(Request $request, Document $document)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $document->status = 'rejected';
        $document->rejected_by = auth()->id();
        $document->rejected_at = now();
        $document->rejection_reason = $request->rejection_reason;
        $document->approved_by = null;
        $document->approved_at = null;
        $document->save();

        return redirect()->back()->with('success', 'เอกสารถูกไม่อนุมัติเรียบร้อยแล้ว');
    }

    // ฟังก์ชันยกเลิกการอนุมัติ
    public function cancelApproval(Document $document)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $document->status = 'pending';
        $document->approved_by = null;
        $document->approved_at = null;
        $document->rejected_by = null;
        $document->rejected_at = null;
        $document->rejection_reason = null;
        $document->save();

        return redirect()->back()->with('success', 'การอนุมัติถูกยกเลิกเรียบร้อยแล้ว');
    }

    // หน้าแสดงเอกสารที่รอการอนุมัติ
    public function pending()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $documents = Document::with(['user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('documents.pending', compact('documents'));
    }

    // หน้าแสดงเอกสารที่อนุมัติแล้ว
    public function approved()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $documents = Document::with(['user', 'approver'])
            ->where('status', 'approved')
            ->orderBy('approved_at', 'desc')
            ->get();

        return view('documents.approved', compact('documents'));
    }

    // หน้าแสดงเอกสารที่ไม่อนุมัติ
    public function rejected()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $documents = Document::with(['user', 'rejecter'])
            ->where('status', 'rejected')
            ->orderBy('rejected_at', 'desc')
            ->get();

        return view('documents.rejected', compact('documents'));
    }
}

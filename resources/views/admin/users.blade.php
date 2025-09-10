@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">จัดการผู้ใช้</h1>
            <a href="{{ route('admin.users') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                รีเฟรช
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อ</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">อีเมล</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">บทบาท</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่สมัคร</th>
                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $user->role === 'admin' ? 'Admin' : 'User' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.users.password-reset', $user->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded text-xs">
                                    รีเซ็ตรหัสผ่าน
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-3 py-1 rounded text-xs">
                                    แก้ไข
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded text-xs">
                                        ลบ
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">ไม่พบข้อมูลผู้ใช้</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

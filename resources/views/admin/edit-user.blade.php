@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">แก้ไขข้อมูลผู้ใช้</h1>
            <p class="text-gray-600">ID: {{ $user->id }}</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    ชื่อ
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $user->name) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="ป้อนชื่อผู้ใช้"
                       required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    อีเมล
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="ป้อนอีเมล"
                       required>
            </div>

            <div class="mb-6">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    บทบาท
                </label>
                <select id="role" 
                        name="role" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="flex space-x-4">
                <button type="submit" 
                        class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    อัปเดตข้อมูล
                </button>
                <a href="{{ route('admin.users') }}" 
                   class="flex-1 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center focus:outline-none focus:shadow-outline">
                    ยกเลิก
                </a>
            </div>
        </form>

        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">ข้อมูลเพิ่มเติม</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>วันที่สมัคร: {{ $user->created_at->format('d/m/Y H:i') }}</p>
                        <p>อัปเดตล่าสุด: {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

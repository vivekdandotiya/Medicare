<x-app-layout>
    <div class="bg-slate-50 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Prescription Hub</h1>
                    <p class="text-slate-500 text-sm mt-1">Upload and manage prescriptions reviewed by our clinical pharmacy staff.</p>
                </div>
            </div>

            <!-- Display Success Message -->
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-250 text-emerald-850 rounded-2xl text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Upload Form (5 Columns) -->
                <div class="lg:col-span-5 bg-white border border-slate-150 rounded-3xl p-6 shadow-sm">
                    <h2 class="text-lg font-extrabold text-slate-800 mb-5">Upload Prescription</h2>
                    
                    <form action="{{ route('prescriptions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Prescription Title</label>
                            <input type="text" name="title" required placeholder="e.g. Cough syrup review" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-teal-500 focus:ring-0">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Patient Full Name</label>
                            <input type="text" name="patient_name" required placeholder="e.g. Jane Doe" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-teal-500 focus:ring-0">
                            @error('patient_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Doctor Name (Optional)</label>
                            <input type="text" name="doctor_name" placeholder="e.g. Dr. Roberts" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-teal-500 focus:ring-0">
                            @error('doctor_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Prescription File (PDF, Image - max 4MB)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl bg-slate-50 hover:bg-slate-100/50 transition cursor-pointer relative">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-slate-650">
                                        <label for="prescription_file" class="relative cursor-pointer bg-transparent rounded-md font-bold text-teal-650 hover:text-teal-800">
                                            <span>Upload a file</span>
                                            <input id="prescription_file" name="prescription_file" type="file" required class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-slate-450">PNG, JPG, PDF up to 4MB</p>
                                </div>
                            </div>
                            @error('prescription_file')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm py-3 rounded-xl transition shadow-md">
                            Submit Prescription
                        </button>
                    </form>
                </div>

                <!-- Right: Prescriptions History List (7 Columns) -->
                <div class="lg:col-span-7 space-y-5">
                    <h2 class="text-lg font-extrabold text-slate-800 mb-2">My Uploaded Prescriptions</h2>

                    @if($prescriptions->isEmpty())
                        <div class="bg-white border border-slate-150 rounded-3xl p-8 text-center text-slate-550">
                            <p class="text-sm font-medium">No prescriptions uploaded yet. Submit your first prescription using the form on the left.</p>
                        </div>
                    @else
                        @foreach($prescriptions as $prescription)
                            <div class="bg-white border border-slate-150 rounded-3xl p-6 shadow-sm space-y-4">
                                <div class="flex justify-between items-start gap-4">
                                    <div>
                                        <h3 class="font-extrabold text-slate-800 text-base leading-tight">{{ $prescription->title }}</h3>
                                        <span class="text-[10px] text-slate-400 font-bold block mt-1">Uploaded: {{ $prescription->created_at->format('M d, Y, h:i A') }}</span>
                                    </div>

                                    <!-- Status Badge -->
                                    <div>
                                        @if($prescription->status === 'pending')
                                            <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-3 py-1 rounded-full border border-amber-200">Pending Review</span>
                                        @elseif($prescription->status === 'approved')
                                            <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-3 py-1 rounded-full border border-emerald-200">Approved</span>
                                        @else
                                            <span class="bg-red-100 text-red-705 text-[10px] font-bold px-3 py-1 rounded-full border border-red-200">Rejected</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-xs border-t border-slate-100 pt-3 text-slate-600 font-medium">
                                    <div>
                                        <span class="text-slate-400 block font-bold text-[9px] uppercase tracking-wider">Patient Name</span>
                                        <span>{{ $prescription->patient_name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block font-bold text-[9px] uppercase tracking-wider">Doctor Name</span>
                                        <span>{{ $prescription->doctor_name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Review Notes -->
                                @if($prescription->notes)
                                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-150 text-xs">
                                        <span class="font-bold text-slate-700 block mb-1">Pharmacist Review Feedback:</span>
                                        <span class="text-slate-500 font-medium leading-relaxed">{{ $prescription->notes }}</span>
                                    </div>
                                @endif

                                <div class="flex justify-between items-center border-t border-slate-100 pt-4">
                                    <a href="{{ asset($prescription->file_path) }}" target="_blank" class="text-xs font-bold text-teal-650 hover:text-teal-850 transition flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Uploaded File
                                    </a>

                                    @if($prescription->status !== 'approved')
                                        <form action="{{ route('prescriptions.destroy', $prescription) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this prescription?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 transition">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-6">
                            {{ $prescriptions->links() }}
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-app-layout>

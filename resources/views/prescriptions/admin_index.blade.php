<x-app-layout>
    <div class="bg-slate-50 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Prescription Verifications</h1>
                    <p class="text-slate-500 text-sm mt-1">Review clinical prescription submissions and assign approval markers.</p>
                </div>

                <!-- Status Filter Options -->
                <div class="flex gap-2">
                    <a href="{{ route('prescriptions.index') }}" class="px-4 py-2 text-xs font-bold rounded-xl border {{ !request('status') ? 'bg-teal-600 border-teal-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }} transition">
                        All
                    </a>
                    <a href="{{ route('prescriptions.index', ['status' => 'pending']) }}" class="px-4 py-2 text-xs font-bold rounded-xl border {{ request('status') === 'pending' ? 'bg-teal-600 border-teal-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }} transition">
                        Pending
                    </a>
                    <a href="{{ route('prescriptions.index', ['status' => 'approved']) }}" class="px-4 py-2 text-xs font-bold rounded-xl border {{ request('status') === 'approved' ? 'bg-teal-600 border-teal-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }} transition">
                        Approved
                    </a>
                    <a href="{{ route('prescriptions.index', ['status' => 'rejected']) }}" class="px-4 py-2 text-xs font-bold rounded-xl border {{ request('status') === 'rejected' ? 'bg-teal-600 border-teal-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }} transition">
                        Rejected
                    </a>
                </div>
            </div>

            <!-- Display Success Message -->
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-250 text-emerald-850 rounded-2xl text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if($prescriptions->isEmpty())
                <div class="bg-white border border-slate-150 rounded-3xl p-12 text-center text-slate-550 shadow-sm">
                    <p class="text-sm font-semibold">No prescriptions found matching filters.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($prescriptions as $prescription)
                        <div class="bg-white border border-slate-150 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                            <div class="space-y-4">
                                <div class="flex justify-between items-start gap-4">
                                    <div>
                                        <h3 class="font-extrabold text-slate-800 text-base leading-tight">{{ $prescription->title }}</h3>
                                        <span class="text-[10px] text-slate-400 font-bold block mt-1">Uploaded by: {{ $prescription->user->name }} ({{ $prescription->user->email }})</span>
                                        <span class="text-[9px] text-slate-450 block font-semibold mt-0.5">Date: {{ $prescription->created_at->format('M d, Y, h:i A') }}</span>
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

                                <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-3 rounded-2xl border border-slate-100 text-slate-655 font-medium">
                                    <div>
                                        <span class="text-slate-400 block font-bold text-[9px] uppercase tracking-wider">Patient Name</span>
                                        <span>{{ $prescription->patient_name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block font-bold text-[9px] uppercase tracking-wider">Doctor Name</span>
                                        <span>{{ $prescription->doctor_name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Action Form (for review) -->
                                <form action="{{ route('prescriptions.update', $prescription) }}" method="POST" class="space-y-4 pt-2 border-t border-slate-100">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Verification Action</label>
                                        <select name="status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:border-teal-500 focus:ring-0">
                                            <option value="">Select Action...</option>
                                            <option value="approved" {{ $prescription->status === 'approved' ? 'selected' : '' }}>Approve Prescription</option>
                                            <option value="rejected" {{ $prescription->status === 'rejected' ? 'selected' : '' }}>Reject Prescription</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Fulfillment Review Notes</label>
                                        <textarea name="notes" placeholder="e.g. Approved. Valid dosage schedule confirmed." rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-850 focus:bg-white focus:border-teal-500 focus:ring-0">{{ $prescription->notes }}</textarea>
                                    </div>

                                    <button type="submit" class="w-full bg-teal-650 hover:bg-teal-750 text-white font-bold text-xs py-2.5 rounded-xl transition shadow-sm">
                                        Update Review
                                    </button>
                                </form>
                            </div>

                            <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center">
                                <a href="{{ asset($prescription->file_path) }}" target="_blank" class="text-xs font-bold text-teal-650 hover:text-teal-850 transition flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    View / Download File
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $prescriptions->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

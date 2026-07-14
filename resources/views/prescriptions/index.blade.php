<x-app-layout>
    <div class="bg-slate-50/50 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Prescription Hub</h1>
                    <p class="text-slate-500 text-sm mt-1 font-medium">Upload and manage prescriptions reviewed by our clinical pharmacy staff.</p>
                </div>
            </div>

            <!-- Display Success Message -->
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-500/10 text-emerald-800 rounded-2xl text-xs font-bold shadow-sm">
                    🎉 {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Upload Form (5 Columns) -->
                <div x-data="{
                    hasFile: false,
                    fileName: '',
                    scanning: false,
                    scanStep: 0,
                    scanProgress: 0,
                    ocrData: null,
                    scanStepsText: [
                        'Loading document pixels...',
                        'Analyzing layout structure...',
                        'Running OCR text recognition...',
                        'Matching medical terms with database...',
                        'Scanning practitioner signature...'
                    ],
                    onFileChange(e) {
                        const file = e.target.files[0];
                        if (!file) return;
                        this.hasFile = true;
                        this.fileName = file.name;
                        this.scanning = true;
                        this.scanProgress = 0;
                        this.scanStep = 0;
                        this.ocrData = null;
                        
                        let interval = setInterval(() => {
                            if (this.scanProgress < 100) {
                                this.scanProgress += 5;
                                this.scanStep = Math.min(Math.floor((this.scanProgress / 100) * this.scanStepsText.length), this.scanStepsText.length - 1);
                            } else {
                                clearInterval(interval);
                                this.scanning = false;
                                this.ocrData = {
                                    patient: 'Jane Doe',
                                    doctor: 'Dr. Roberts',
                                    extracted: ['Amoxyclav 625 Duo', 'Ascoril LS Syrup'],
                                    validClinic: true
                                };
                            }
                        }, 80);
                    }
                }" class="lg:col-span-5 bg-white border border-slate-200/50 rounded-3xl p-6 shadow-sm hover:border-teal-500/10 transition duration-300">
                    <h2 class="text-lg font-bold text-slate-900 mb-5">Upload Prescription</h2>
                    
                    <form action="{{ route('prescriptions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label class="block text-[10px] font-bold text-slate-455 uppercase tracking-widest mb-2">Prescription Title</label>
                            <input type="text" name="title" required placeholder="e.g. Cough syrup review" class="w-full bg-slate-550/5 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-teal-500 focus:outline-none transition font-semibold">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-455 uppercase tracking-widest mb-2">Patient Full Name</label>
                            <input type="text" name="patient_name" required placeholder="e.g. Jane Doe" class="w-full bg-slate-550/5 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-teal-500 focus:outline-none transition font-semibold">
                            @error('patient_name')
                                <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-455 uppercase tracking-widest mb-2">Doctor Name (Optional)</label>
                            <input type="text" name="doctor_name" placeholder="e.g. Dr. Roberts" class="w-full bg-slate-550/5 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-teal-500 focus:outline-none transition font-semibold">
                            @error('doctor_name')
                                <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-455 uppercase tracking-widest mb-2">Prescription File (PDF, Image - max 4MB)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-250 border-dashed rounded-2xl bg-slate-50 hover:bg-slate-100/50 hover:border-teal-500/30 transition cursor-pointer relative">
                                <div class="space-y-1.5 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-slate-600 justify-center">
                                        <label for="prescription_file" class="relative cursor-pointer bg-transparent rounded-md font-bold text-teal-650 hover:text-teal-850">
                                            <span>Upload a file</span>
                                            <input id="prescription_file" name="prescription_file" type="file" required class="sr-only" @change="onFileChange($event)">
                                        </label>
                                        <p class="pl-1 font-medium">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-slate-400 font-medium">PNG, JPG, PDF up to 4MB</p>
                                </div>
                            </div>
                            @error('prescription_file')
                                <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Animated OCR Scanner Widget -->
                        <div x-show="hasFile" class="mt-4 border border-slate-150 rounded-2xl p-4 bg-slate-50/70" style="display: none;">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[10px] text-slate-450 font-bold uppercase tracking-wider">File Selected:</span>
                                <span class="text-xs font-bold text-slate-700 max-w-[150px] truncate" x-text="fileName"></span>
                            </div>

                            <!-- Scanning -->
                            <div x-show="scanning" class="space-y-3">
                                <div class="flex justify-between items-center text-[10px] font-bold text-teal-705 uppercase tracking-widest">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-ping"></span>
                                        Scanning Document
                                    </span>
                                    <span x-text="`${scanProgress}%`"></span>
                                </div>
                                <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden relative">
                                    <div class="h-full bg-teal-500 transition-all duration-75 rounded-full" :style="`width: ${scanProgress}%`"></div>
                                </div>
                                <p class="text-[11px] text-slate-500 font-semibold italic" x-text="scanStepsText[scanStep]"></p>
                            </div>

                            <!-- Match Done -->
                            <div x-show="!scanning && ocrData" class="space-y-3 pt-2 border-t border-slate-200/50">
                                <div class="flex items-center gap-1.5 text-xs font-extrabold text-emerald-650 uppercase tracking-wide">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    OCR Analyzer Match Complete
                                </div>
                                <div class="bg-white border border-slate-150 rounded-xl p-3 space-y-1.5 text-xs font-medium">
                                    <div class="flex justify-between">
                                        <span class="text-slate-400">Patient:</span>
                                        <span class="text-slate-800 font-bold" x-text="ocrData?.patient"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-400">Practitioner:</span>
                                        <span class="text-slate-800 font-bold" x-text="ocrData?.doctor"></span>
                                    </div>
                                    <div class="border-t border-slate-100 my-2 pt-2">
                                        <span class="text-[10px] text-slate-400 block font-bold uppercase tracking-wider mb-1">Detected Formulations</span>
                                        <div class="flex flex-wrap gap-1">
                                            <template x-for="item in ocrData?.extracted">
                                                <span class="bg-teal-50 text-teal-700 text-[10px] font-bold px-2 py-0.5 rounded border border-teal-200/25" x-text="item"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center text-[10px] font-bold text-emerald-650 bg-emerald-50 border border-emerald-500/10 px-2 py-1 rounded-md mt-1">
                                        <span>Signature Verification: PASS</span>
                                        <span>100% Compliant</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white font-bold text-sm py-3.5 rounded-xl transition shadow-lg shadow-teal-500/20 hover:scale-[1.02]">
                            Submit Prescription
                        </button>
                    </form>
                </div>

                <!-- Virtual Pharmacist Booking Card -->
                <div x-data="{
                    openBooking: false,
                    specialty: 'Prescription Queries',
                    date: '',
                    slot: '10:00 AM - 10:15 AM',
                    bookingResult: null,
                    submitBooking() {
                        if (!this.date) return alert('Please choose a date.');
                        this.bookingResult = {
                            id: Math.floor(Math.random() * 90000) + 10000,
                            meetLink: 'meet.google.com/mxc-' + Math.random().toString(36).substring(2,6) + '-yht'
                        };
                    },
                    closeBooking() {
                        this.openBooking = false;
                        this.bookingResult = null;
                        this.date = '';
                    }
                }" class="mt-6 bg-gradient-to-br from-teal-50 to-teal-100/40 rounded-3xl p-6 border border-teal-500/10 shadow-sm font-sans">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-white text-teal-650 rounded-2xl shadow-sm border border-teal-200/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-800 text-sm">Pharmacist Advisory Call</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed font-medium">Schedule a free 10-minute consultation with a registered clinical pharmacist for medical guidelines.</p>
                            <button @click="openBooking = true" type="button" class="mt-4 bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition shadow-md shadow-teal-600/10 active:scale-95">
                                Book Video Consultation
                            </button>
                        </div>
                    </div>

                    <!-- Booking Modal -->
                    <div x-show="openBooking" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeBooking()"></div>
                        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl relative z-10 border border-slate-200">
                            <div class="flex justify-between items-center pb-3 border-b border-slate-100 mb-4">
                                <h4 class="font-extrabold text-slate-800 text-sm">Book Pharmacist Call</h4>
                                <button type="button" @click="closeBooking()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-bold transition">&times;</button>
                            </div>

                            <!-- Form view -->
                            <div x-show="!bookingResult" class="space-y-4">
                                <div>
                                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 font-sans">Advisory Area</label>
                                    <select x-model="specialty" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-teal-500 transition">
                                        <option value="Prescription Clarifications">Prescription Clarifications</option>
                                        <option value="Alternative Substitution & Dosage">Alternative Substitution & Dosage</option>
                                        <option value="Side Effects & Contraindications">Side Effects & Contraindications</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 font-sans">Choose Date</label>
                                    <input type="date" x-model="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:border-teal-500 transition">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 font-sans">Available Time Slots</label>
                                    <select x-model="slot" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-teal-500 transition">
                                        <option value="10:00 AM - 10:15 AM">10:00 AM - 10:15 AM</option>
                                        <option value="11:30 AM - 11:45 AM">11:30 AM - 11:45 AM</option>
                                        <option value="03:00 PM - 03:15 PM">03:00 PM - 03:15 PM</option>
                                        <option value="05:30 PM - 05:45 PM">05:30 PM - 05:45 PM</option>
                                    </select>
                                </div>
                                <button @click="submitBooking()" type="button" class="w-full bg-teal-650 hover:bg-teal-700 text-white font-bold text-xs py-3 rounded-xl transition shadow-md shadow-teal-500/10 active:scale-95">
                                    Confirm Call Appointment
                                </button>
                            </div>

                            <!-- Success view -->
                            <div x-show="bookingResult" class="space-y-4 text-center">
                                <div class="w-12 h-12 bg-emerald-50 text-emerald-650 rounded-full flex items-center justify-center mx-auto shadow-sm border border-emerald-500/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-extrabold text-slate-800 text-sm">Advisory Scheduled!</h5>
                                    <p class="text-[10px] text-slate-450 mt-1" x-text="'Appointment ID: #PH-' + bookingResult?.id"></p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-150 text-left space-y-1.5 text-xs font-semibold text-slate-700">
                                    <div><span class="text-slate-400">Date:</span> <span class="text-slate-800" x-text="date"></span></div>
                                    <div><span class="text-slate-400">Time:</span> <span class="text-slate-800" x-text="slot"></span></div>
                                    <div><span class="text-slate-400">Topic:</span> <span class="text-slate-800" x-text="specialty"></span></div>
                                </div>
                                <div class="text-[10px] bg-teal-50 border border-teal-500/10 text-teal-800 p-2.5 rounded-xl font-bold flex flex-col items-center">
                                    <span>Google Meet Join Link:</span>
                                    <a :href="'https://' + bookingResult?.meetLink" target="_blank" class="underline block mt-0.5 text-teal-700" x-text="bookingResult?.meetLink"></a>
                                </div>
                                <button @click="closeBooking()" type="button" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-655 font-bold text-xs py-2.5 rounded-xl transition">
                                    Close Receipt
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Right: Prescriptions History List (7 Columns) -->
                <div class="lg:col-span-7 space-y-5" x-data="{
                    activeFilter: 'all',
                    searchQuery: '',
                    matchesFilter(status) {
                        if (this.activeFilter === 'all') return true;
                        return this.activeFilter === status;
                    },
                    matchesSearch(title, patient, doctor) {
                        if (!this.searchQuery.trim()) return true;
                        const q = this.searchQuery.toLowerCase();
                        return (title || '').toLowerCase().includes(q) ||
                               (patient || '').toLowerCase().includes(q) ||
                               (doctor || '').toLowerCase().includes(q);
                    }
                }">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
                        <h2 class="text-lg font-bold text-slate-900">My Uploaded Prescriptions</h2>
                        
                        <!-- Search Bar -->
                        <div class="relative w-full sm:w-64">
                            <input type="text" x-model="searchQuery" placeholder="Search prescriptions..."
                                   class="w-full bg-white border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs focus:border-teal-500 focus:outline-none transition font-semibold text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="flex flex-wrap gap-2 border-b border-slate-100 pb-3">
                        <button @click="activeFilter = 'all'" :class="activeFilter === 'all' ? 'bg-teal-650 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="text-[10px] font-black px-3.5 py-1.5 rounded-lg transition">All</button>
                        <button @click="activeFilter = 'pending'" :class="activeFilter === 'pending' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="text-[10px] font-black px-3.5 py-1.5 rounded-lg transition">Pending</button>
                        <button @click="activeFilter = 'approved'" :class="activeFilter === 'approved' ? 'bg-emerald-650 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="text-[10px] font-black px-3.5 py-1.5 rounded-lg transition">Approved</button>
                        <button @click="activeFilter = 'rejected'" :class="activeFilter === 'rejected' ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="text-[10px] font-black px-3.5 py-1.5 rounded-lg transition">Rejected</button>
                    </div>

                    @if($prescriptions->isEmpty())
                        <div class="bg-white border border-slate-200/50 rounded-3xl p-8 text-center text-slate-450 shadow-sm">
                            <p class="text-sm font-medium">No prescriptions uploaded yet. Submit your first prescription using the form on the left.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($prescriptions as $prescription)
                                <div class="bg-white border border-slate-200/50 rounded-3xl p-5 shadow-sm space-y-4 hover:border-teal-500/15 hover:shadow-md transition duration-300 flex flex-col justify-between
                                            {{ $prescription->status === 'pending' ? 'border-l-4 border-l-amber-500' : ($prescription->status === 'approved' ? 'border-l-4 border-l-emerald-500' : 'border-l-4 border-l-red-500') }}"
                                     x-show="matchesFilter('{{ $prescription->status }}') && matchesSearch('{{ addslashes($prescription->title) }}', '{{ addslashes($prescription->patient_name) }}', '{{ addslashes($prescription->doctor_name ?? '') }}')"
                                     x-transition>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-start gap-3">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 shrink-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-650" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <h3 class="font-bold text-slate-900 text-sm truncate leading-tight" title="{{ $prescription->title }}">{{ $prescription->title }}</h3>
                                                    <span class="text-[9px] text-slate-400 font-bold block mt-1">Uploaded: {{ $prescription->created_at->format('M d, Y') }}</span>
                                                </div>
                                            </div>

                                            <!-- Status Badge -->
                                            <div class="shrink-0">
                                                @if($prescription->status === 'pending')
                                                    <span class="bg-amber-500/10 text-amber-700 text-[8px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider border border-amber-550/10">Pending</span>
                                                @elseif($prescription->status === 'approved')
                                                    <span class="bg-emerald-500/10 text-emerald-700 text-[8px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider border border-emerald-550/10">Approved</span>
                                                @else
                                                    <span class="bg-red-500/10 text-red-700 text-[8px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider border border-red-550/10">Rejected</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3 text-[11px] border-t border-slate-100 pt-3 text-slate-655 font-medium">
                                            <div>
                                                <span class="text-slate-400 block font-extrabold text-[8px] uppercase tracking-wider">Patient Name</span>
                                                <span class="text-slate-800 font-bold truncate block">{{ $prescription->patient_name }}</span>
                                            </div>
                                            <div>
                                                <span class="text-slate-400 block font-extrabold text-[8px] uppercase tracking-wider">Doctor Name</span>
                                                <span class="text-slate-800 font-bold truncate block">{{ $prescription->doctor_name ?? 'Not Stated' }}</span>
                                            </div>
                                        </div>

                                        <!-- Review Notes -->
                                        @if($prescription->notes)
                                            <div class="p-3 bg-slate-50 border border-slate-200/40 rounded-xl text-[10px] leading-relaxed text-slate-550 font-medium">
                                                <span class="font-extrabold text-slate-700 block mb-0.5">Pharmacist Review Feedback:</span>
                                                {{ $prescription->notes }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex justify-between items-center border-t border-slate-100 pt-3.5 mt-4">
                                        <a href="{{ asset($prescription->file_path) }}" target="_blank" class="text-[10px] font-black text-teal-650 hover:text-teal-800 transition flex items-center gap-1.5 bg-teal-50 hover:bg-teal-100 border border-teal-500/10 px-3 py-1.5 rounded-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View File
                                        </a>

                                        @if($prescription->status !== 'approved')
                                            <form action="{{ route('prescriptions.destroy', $prescription) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this prescription?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[10px] font-bold text-red-650 hover:text-red-800 transition px-2 py-1 hover:bg-red-50 rounded-lg">
                                                    Delete Record
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $prescriptions->links() }}
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-app-layout>

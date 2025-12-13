<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Digital Signature') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Upload or draw your digital signature for automated document signing.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data" x-data="signaturePad()">
        @csrf
        @method('patch')

        <div class="space-y-4">
            <!-- Current Signature Display -->
            @if($user->signature)
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <p class="text-sm font-medium text-gray-700 mb-2">{{ __('Current Signature') }}</p>
                    <div class="bg-white p-2 rounded border border-dashed border-gray-300 inline-block">
                        <img src="{{ asset('storage/' . $user->signature) }}" alt="Signature" class="h-16 object-contain">
                    </div>
                </div>
            @endif

            <!-- Tabs -->
            <div class="flex gap-4 border-b border-gray-200">
                <button type="button" @click="mode = 'upload'" 
                        :class="mode === 'upload' ? 'border-[#5f674d] text-[#5f674d]' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                    Upload Image
                </button>
                <button type="button" @click="mode = 'draw'; $nextTick(() => resizeCanvas())"
                        :class="mode === 'draw' ? 'border-[#5f674d] text-[#5f674d]' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                    Draw Signature
                </button>
            </div>

            <!-- Upload Input -->
            <div x-show="mode === 'upload'" class="space-y-2">
                <input id="signature_upload" name="signature" type="file" accept="image/*" 
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#5f674d]/10 file:text-[#5f674d] hover:file:bg-[#5f674d]/20 transition-all cursor-pointer" />
                <p class="text-xs text-gray-400">{{ __('Format: JPG/PNG/WEBP. Max 1MB.') }}</p>
            </div>

            <!-- Drawing Canvas -->
            <div x-show="mode === 'draw'" style="display: none;">
                <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg relative touch-none hover:border-[#5f674d] transition-colors overflow-hidden">
                    <canvas x-ref="canvas" 
                            @mousedown="startDrawing" 
                            @mousemove="draw" 
                            @mouseup="stopDrawing" 
                            @mouseleave="stopDrawing"
                            @touchstart.prevent="startDrawing" 
                            @touchmove.prevent="draw" 
                            @touchend.prevent="stopDrawing"
                            class="w-full h-48 cursor-crosshair bg-white"></canvas>
                    
                    <div class="absolute top-2 right-2 flex gap-2">
                         <button type="button" @click="clearCanvas()" class="text-xs bg-white/80 backdrop-blur px-2 py-1 rounded border border-gray-200 text-gray-500 hover:text-red-500 hover:border-red-500 transition-colors shadow-sm">
                            {{ __('Clear') }}
                        </button>
                    </div>
                    
                    <div x-show="isEmpty" class="absolute inset-0 flex items-center justify-center pointer-events-none text-gray-300 text-sm">
                        {{ __('Sign here') }}
                    </div>
                </div>
                
                <!-- Hidden Input for Data URL -->
                <input type="hidden" name="signature_data" x-model="signatureData">
                <p class="text-xs text-gray-400 mt-2">{{ __('Draw your signature inside the box above.') }}</p>
            </div>
            
             <x-input-error class="mt-2" :messages="$errors->get('signature')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Update Signature') }}</x-primary-button>
        </div>
    </form>

    <script>
        function signaturePad() {
            return {
                mode: 'upload', // upload | draw
                isDrawing: false,
                context: null,
                isEmpty: true,
                signatureData: '',
                
                init() {
                    const canvas = this.$refs.canvas;
                    this.context = canvas.getContext('2d');
                    
                    // Set canvas resolution for sharper lines
                    this.resizeCanvas();
                    window.addEventListener('resize', () => this.resizeCanvas());
                    
                    // Style
                    this.context.lineCap = 'round';
                    this.context.lineJoin = 'round';
                    this.context.strokeStyle = '#000000';
                    this.context.lineWidth = 2;
                },

                resizeCanvas() {
                    const canvas = this.$refs.canvas;
                    const rect = canvas.getBoundingClientRect();
                    // Fix resolution
                    canvas.width = rect.width * window.devicePixelRatio;
                    canvas.height = rect.height * window.devicePixelRatio;
                    
                    this.context.scale(window.devicePixelRatio, window.devicePixelRatio);
                    
                    // Reset styling after resize as it might be lost
                    this.context.lineCap = 'round';
                    this.context.lineJoin = 'round';
                    this.context.strokeStyle = '#000000';
                    this.context.lineWidth = 2;
                    
                    // Manually size style to match
                    canvas.style.width = '100%';
                    canvas.style.height = '192px'; // h-48
                },

                getPos(e) {
                    const canvas = this.$refs.canvas;
                    const rect = canvas.getBoundingClientRect();
                    
                    let clientX = e.clientX;
                    let clientY = e.clientY;
                    
                    if (e.touches && e.touches.length > 0) {
                        clientX = e.touches[0].clientX;
                        clientY = e.touches[0].clientY;
                    }
                    
                    return {
                        x: clientX - rect.left,
                        y: clientY - rect.top
                    };
                },

                startDrawing(e) {
                    this.isDrawing = true;
                    this.isEmpty = false;
                    const pos = this.getPos(e);
                    this.context.beginPath();
                    this.context.moveTo(pos.x, pos.y);
                },

                draw(e) {
                    if (!this.isDrawing) return;
                    const pos = this.getPos(e);
                    this.context.lineTo(pos.x, pos.y);
                    this.context.stroke();
                },

                stopDrawing() {
                    if (this.isDrawing) {
                        this.isDrawing = false;
                        this.saveSignature();
                    }
                },

                clearCanvas() {
                    const canvas = this.$refs.canvas;
                    this.context.clearRect(0, 0, canvas.width, canvas.height); // clear based on internal size
                    this.isEmpty = true;
                    this.signatureData = '';
                },
                
                saveSignature() {
                    this.signatureData = this.$refs.canvas.toDataURL('image/png');
                }
            }
        }
    </script>
</section>

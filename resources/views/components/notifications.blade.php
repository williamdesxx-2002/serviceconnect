<!-- Notification Component -->
@if(session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show animate-fade-in-right" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show animate-fade-in-right" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2"></i>
            <div class="flex-grow-1">{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if(session()->has('warning'))
    <div class="alert alert-warning alert-dismissible fade show animate-fade-in-right" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div class="flex-grow-1">{{ session('warning') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if(session()->has('info'))
    <div class="alert alert-info alert-dismissible fade show animate-fade-in-right" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle me-2"></i>
            <div class="flex-grow-1">{{ session('info') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show animate-fade-in-right" role="alert">
        <div class="d-flex align-items-start">
            <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
            <div class="flex-grow-1">
                <strong>Erreur(s) détectée(s) :</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

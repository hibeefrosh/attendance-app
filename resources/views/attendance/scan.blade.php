@extends('layouts.app')

@section('title', 'Scan QR Code')

@section('scan')
<div class="scan-screen">
    <div class="scan-topbar">
        <a href="{{ route('dashboard') }}" class="scan-icon-btn" aria-label="Back"><i class="bi bi-arrow-left"></i></a>
        <h1 class="scan-title">Scan QR Code</h1>
        <button type="button" class="scan-icon-btn" id="torchBtn" aria-label="Flash"><i class="bi bi-lightning-charge"></i></button>
    </div>

    <p class="scan-hint">
        Scan the QR code displayed by your lecturer to mark attendance
    </p>

    <div class="scan-frame-wrap scan-corners">
        <span></span>
        <div id="reader"></div>
    </div>

    <p class="scan-status" id="scanStatus">Ensure the code is within the frame</p>

    <div class="text-center mt-2 mb-2">
        <button class="btn btn-link text-white-50 small" type="button" data-bs-toggle="collapse" data-bs-target="#manualToken">
            Enter token manually
        </button>
        <div class="collapse px-3" id="manualToken">
            <form id="manualForm" class="mt-2">
                <div class="input-group input-group-sm">
                    <input type="text" id="tokenInput" class="form-control" maxlength="64" placeholder="Session token">
                    <button class="btn btn-light" type="submit">Go</button>
                </div>
            </form>
        </div>
    </div>

    @include('layouts.partials.student-nav', ['navDark' => true])
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(() => {
    const statusEl = document.getElementById('scanStatus');
    const markUrl = @json(route('attendance.mark'));
    let busy = false;
    let lastToken = null;
    let scanner = null;

    function setStatus(message) {
        statusEl.textContent = message;
    }

    async function submitToken(token) {
        token = (token || '').trim();
        if (!token || busy || token === lastToken) return;

        busy = true;
        lastToken = token;
        App.setLoading(true);
        setStatus('Validating attendance…');

        try {
            const res = await fetch(markUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': App.csrfToken(),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ token })
            });
            const data = await res.json();

            if (data.success && data.redirect) {
                setStatus('Attendance marked!');
                window.location.href = data.redirect;
                return;
            }

            setStatus(data.message || 'Unable to mark attendance.');
            App.showToast(data.message || 'Unable to mark attendance.', 'error');
            lastToken = null;
        } catch (e) {
            setStatus('Network error. Please try again.');
            lastToken = null;
        } finally {
            busy = false;
            App.setLoading(false);
        }
    }

    document.getElementById('manualForm')?.addEventListener('submit', (e) => {
        e.preventDefault();
        submitToken(document.getElementById('tokenInput').value);
    });

    document.getElementById('torchBtn')?.addEventListener('click', async () => {
        try {
            if (scanner && scanner.getRunningTrackCapabilities) {
                const caps = scanner.getRunningTrackCapabilities();
                if (caps && caps.torch) {
                    await scanner.applyVideoConstraints({ advanced: [{ torch: true }] });
                }
            }
        } catch (e) {}
    });

    scanner = new Html5Qrcode('reader');
    Html5Qrcode.getCameras().then(cameras => {
        if (!cameras.length) {
            setStatus('No camera found. Use manual token entry.');
            return;
        }
        const cameraId = cameras[cameras.length - 1].id;
        scanner.start(
            cameraId,
            { fps: 10, qrbox: { width: 220, height: 220 } },
            (decodedText) => submitToken(decodedText),
            () => {}
        ).then(() => setStatus('Ensure the code is within the frame'))
         .catch(() => setStatus('Camera permission denied.'));
    }).catch(() => setStatus('Camera access denied. Use manual token entry.'));
})();
</script>
@endpush

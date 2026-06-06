<?php
/**
 * Network Speed Test View
 */
?>
<style>
@media (min-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1.2fr 1fr !important;
    }
    .info-grid {
        grid-template-columns: 1fr 1fr !important;
    }
}
</style>
<div class="tool-container" style="max-width: 950px; margin-inline: auto; padding-top: 2rem;">
    <!-- Tool Header -->
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; background: linear-gradient(135deg, var(--color-primary), #10B981); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            Network Speed Test
        </h1>
        <p class="text-muted">Measure your download speed, upload speed, latency, and jitter in real-time with an interactive gauge.</p>
    </div>

    <!-- Main Dashboard -->
    <div class="premium-card" style="padding: 2.5rem; margin-bottom: 2.5rem; position: relative; overflow: hidden;">
        <!-- Background Glows for Premium Vibe -->
        <div style="position: absolute; top: -100px; left: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, rgba(255,255,255,0) 70%); pointer-events: none;"></div>
        <div style="position: absolute; bottom: -100px; right: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, rgba(255,255,255,0) 70%); pointer-events: none;"></div>

        <!-- Upper Status Row -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <span id="test-status-badge" style="background: rgba(156, 163, 175, 0.1); color: var(--color-text-secondary); font-size: 0.8rem; font-weight: 600; padding: 0.35rem 0.85rem; border-radius: var(--radius-full); text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid var(--color-border);">
                    Ready
                </span>
                <span id="test-progress-text" class="text-sm text-muted" style="margin-left: 0.75rem;">Click Start to begin testing</span>
            </div>
            
            <div id="ip-info-display" class="text-sm text-muted" style="display: flex; gap: 1rem; align-items: center;">
                <div><i class="fa-solid fa-globe" style="color: var(--color-primary); margin-right: 0.25rem;"></i> IP: <span id="client-ip" style="font-weight: 600; color: var(--color-text-primary);"><?php echo htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'); ?></span></div>
            </div>
        </div>

        <!-- Central Layout Grid -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 2.5rem; align-items: center; margin-bottom: 2rem;">
            <div class="dashboard-grid" style="display: grid; grid-template-columns: 1fr; gap: 2.5rem; align-items: center;">
                
                <!-- Left Column: Gauge & Speed Indicator -->
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative;">
                    <!-- SVG Radial Gauge -->
                    <div style="position: relative; width: 280px; height: 280px;">
                        <svg width="280" height="280" viewBox="0 0 200 200" style="transform: rotate(-90deg);">
                            <!-- Background Track -->
                            <circle cx="100" cy="100" r="85" fill="none" stroke="var(--color-border)" stroke-width="10" stroke-dasharray="400 135" stroke-linecap="round" />
                            
                            <!-- Dynamic Speed Track -->
                            <circle id="gauge-fill" cx="100" cy="100" r="85" fill="none" stroke="url(#gauge-gradient)" stroke-width="12" stroke-dasharray="0 534" stroke-linecap="round" style="transition: stroke-dasharray 200ms ease-out;" />
                            
                            <!-- Gradients Definition -->
                            <defs>
                                <linearGradient id="gauge-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#3B82F6" />
                                    <stop offset="100%" stop-color="#10B981" />
                                </linearGradient>
                            </defs>
                        </svg>

                        <!-- Central Speed Display -->
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; pointer-events: none;">
                            <span id="speed-value" style="font-size: 3.5rem; font-weight: 800; font-family: monospace; line-height: 1; margin-bottom: 0.25rem; background: linear-gradient(135deg, var(--color-text-primary) 30%, var(--color-text-secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">0.0</span>
                            <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-text-secondary); text-transform: uppercase; letter-spacing: 0.1em;">Mbps</span>
                            <span id="phase-label" style="font-size: 0.75rem; font-weight: 600; color: var(--color-primary); margin-top: 0.5rem; min-height: 18px; text-transform: uppercase;"></span>
                        </div>
                    </div>

                    <!-- Sparkline Consistency Graph -->
                    <div style="width: 100%; max-width: 320px; margin-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                            <span class="text-xs text-muted">Real-Time Consistency</span>
                            <span id="consistency-percent" class="text-xs font-semibold text-primary">0%</span>
                        </div>
                        <div style="height: 50px; background: rgba(156, 163, 175, 0.05); border: 1px solid var(--color-border); border-radius: var(--radius-md); overflow: hidden;">
                            <canvas id="speed-chart" width="320" height="50" style="display: block; width: 100%; height: 100%;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Status Metric Cards -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    
                    <!-- Latency (Ping) -->
                    <div class="metric-card" style="background: rgba(156, 163, 175, 0.03); border: 1px solid var(--color-border); padding: 1.25rem; border-radius: var(--radius-lg); position: relative; transition: all 0.3s ease;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <span class="text-xs text-muted" style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Ping</span>
                            <i class="fa-solid fa-arrow-right-arrow-left" style="color: #6B7280; font-size: 0.85rem;"></i>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 0.25rem;">
                            <span id="metric-ping" style="font-size: 1.75rem; font-weight: 700; font-family: monospace;">- -</span>
                            <span class="text-xs text-muted">ms</span>
                        </div>
                    </div>

                    <!-- Jitter -->
                    <div class="metric-card" style="background: rgba(156, 163, 175, 0.03); border: 1px solid var(--color-border); padding: 1.25rem; border-radius: var(--radius-lg); position: relative; transition: all 0.3s ease;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <span class="text-xs text-muted" style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Jitter</span>
                            <i class="fa-solid fa-wave-square" style="color: #6B7280; font-size: 0.85rem;"></i>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 0.25rem;">
                            <span id="metric-jitter" style="font-size: 1.75rem; font-weight: 700; font-family: monospace;">- -</span>
                            <span class="text-xs text-muted">ms</span>
                        </div>
                    </div>

                    <!-- Download Speed -->
                    <div id="card-download" class="metric-card" style="background: rgba(156, 163, 175, 0.03); border: 1px solid var(--color-border); padding: 1.25rem; border-radius: var(--radius-lg); position: relative; transition: all 0.3s ease; grid-column: span 2;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <span class="text-xs text-muted" style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Download Speed</span>
                            <i class="fa-solid fa-circle-down" style="color: var(--color-primary); font-size: 1rem;"></i>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 0.25rem; margin-bottom: 0.25rem;">
                            <span id="metric-download" style="font-size: 2rem; font-weight: 800; font-family: monospace; color: var(--color-text-primary); transition: color 0.3s;">- -</span>
                            <span class="text-xs text-muted">Mbps</span>
                        </div>
                        <div class="progress-bar-container" style="height: 4px; background: var(--color-border); border-radius: var(--radius-full); overflow: hidden; display: none;">
                            <div id="progress-download" style="height: 100%; width: 0%; background: linear-gradient(90deg, #3b82f6, #10b981); transition: width 0.1s linear;"></div>
                        </div>
                    </div>

                    <!-- Upload Speed -->
                    <div id="card-upload" class="metric-card" style="background: rgba(156, 163, 175, 0.03); border: 1px solid var(--color-border); padding: 1.25rem; border-radius: var(--radius-lg); position: relative; transition: all 0.3s ease; grid-column: span 2;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <span class="text-xs text-muted" style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Upload Speed</span>
                            <i class="fa-solid fa-circle-up" style="color: #10B981; font-size: 1rem;"></i>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 0.25rem; margin-bottom: 0.25rem;">
                            <span id="metric-upload" style="font-size: 2rem; font-weight: 800; font-family: monospace; color: var(--color-text-primary); transition: color 0.3s;">- -</span>
                            <span class="text-xs text-muted">Mbps</span>
                        </div>
                        <div class="progress-bar-container" style="height: 4px; background: var(--color-border); border-radius: var(--radius-full); overflow: hidden; display: none;">
                            <div id="progress-upload" style="height: 100%; width: 0%; background: linear-gradient(90deg, #10b981, #3b82f6); transition: width 0.1s linear;"></div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- Start/Reset Actions Control -->
        <div style="display: flex; justify-content: center; gap: 1rem;">
            <button id="start-test-btn" class="btn btn-primary" style="padding: 0.85rem 2.5rem; font-size: 1.1rem; font-weight: 600; background: linear-gradient(135deg, var(--color-primary), #10B981); border: none; box-shadow: 0 4px 14px rgba(59, 130, 246, 0.25); border-radius: var(--radius-full);">
                <i class="fa-solid fa-play" style="margin-right: 0.5rem;"></i> Run Speed Test
            </button>
            <button id="cancel-test-btn" class="btn btn-secondary" style="padding: 0.85rem 2rem; font-size: 1.1rem; font-weight: 600; border-radius: var(--radius-full); display: none;">
                <i class="fa-solid fa-stop" style="margin-right: 0.5rem;"></i> Cancel
            </button>
        </div>
    </div>

    <!-- Informational Card -->
    <div class="premium-card" style="padding: 2rem; font-size: 0.95rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;"><i class="fa-solid fa-circle-info" style="color: var(--color-primary); margin-right: 0.5rem;"></i> Understanding Your Speed Test Results</h3>
        <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
            <div class="info-grid" style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 0.25rem; color: var(--color-text-primary);">Download Speed</h4>
                    <p class="text-muted text-sm">How fast data is transferred from servers to your device. Vital for video streaming, loading web pages, and downloading large files.</p>
                </div>
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 0.25rem; color: var(--color-text-primary);">Upload Speed</h4>
                    <p class="text-muted text-sm">How fast data is sent from your device to the network. Crucial for video conferencing, online gaming, and publishing files online.</p>
                </div>
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 0.25rem; color: var(--color-text-primary);">Ping / Latency</h4>
                    <p class="text-muted text-sm">The round-trip delay time for data to travel from your device to the server and back. Lower latency values are highly preferred for gaming and calls.</p>
                </div>
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 0.25rem; color: var(--color-text-primary);">Jitter</h4>
                    <p class="text-muted text-sm">The fluctuation in latency over time. Low jitter represents a stable, consistent connection, while high jitter can cause voice dropouts or lagging.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // DOM Elements
    const startBtn = document.getElementById("start-test-btn");
    const cancelBtn = document.getElementById("cancel-test-btn");
    const statusBadge = document.getElementById("test-status-badge");
    const progressText = document.getElementById("test-progress-text");
    const speedVal = document.getElementById("speed-value");
    const phaseLabel = document.getElementById("phase-label");
    const clientIpSpan = document.getElementById("client-ip");
    
    const metricPing = document.getElementById("metric-ping");
    const metricJitter = document.getElementById("metric-jitter");
    const metricDownload = document.getElementById("metric-download");
    const metricUpload = document.getElementById("metric-upload");
    
    const progressContainerDownload = document.querySelector("#card-download .progress-bar-container");
    const progressDownload = document.getElementById("progress-download");
    const progressContainerUpload = document.querySelector("#card-upload .progress-bar-container");
    const progressUpload = document.getElementById("progress-upload");
    
    const gaugeFill = document.getElementById("gauge-fill");
    const canvas = document.getElementById("speed-chart");
    const ctx = canvas.getContext("2d");
    const consistencyPercent = document.getElementById("consistency-percent");

    // Constants
    const TEST_DURATION = 10000; // 10 seconds for transfer phases
    const GAUGE_MAX_SPEED = 100; // max scale in Mbps

    // State Variables
    let activeController = null;
    let isTesting = false;
    let testHistory = [];

    // Fetch IP Address
    fetch('https://api.ipify.org?format=json')
        .then(res => res.json())
        .then(data => {
            if (data.ip) {
                clientIpSpan.textContent = data.ip;
            }
        })
        .catch(() => {
            clientIpSpan.textContent = "Unavailable";
        });

    // Gauge Update Helper
    function updateGauge(speed) {
        // SVG circle perimeter is 2 * PI * r = 2 * 3.14159 * 85 ≈ 534
        // The visible range of the gauge arc is ~400 units of the stroke
        const maxDash = 400;
        const normalized = Math.min(speed / GAUGE_MAX_SPEED, 1.0);
        const dashOffset = normalized * maxDash;
        gaugeFill.style.strokeDasharray = `${dashOffset} 534`;
    }

    // Sparkline Plotting Helper
    function initSparkline() {
        testHistory = [];
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        consistencyPercent.textContent = "0%";
    }

    function addSparklinePoint(val) {
        testHistory.push(val);
        if (testHistory.length > 50) {
            testHistory.shift();
        }

        // Draw graph
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Gradient fill
        const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
        
        ctx.beginPath();
        ctx.strokeStyle = '#3B82F6';
        ctx.lineWidth = 2.5;
        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';

        const step = canvas.width / 49;
        const maxVal = Math.max(10, ...testHistory);

        for (let i = 0; i < testHistory.length; i++) {
            const x = i * step;
            const y = canvas.height - (testHistory[i] / maxVal) * (canvas.height - 10) - 5;
            if (i === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        }
        ctx.stroke();

        // Fill path below stroke
        if (testHistory.length > 0) {
            ctx.lineTo((testHistory.length - 1) * step, canvas.height);
            ctx.lineTo(0, canvas.height);
            ctx.closePath();
            ctx.fillStyle = gradient;
            ctx.fill();
        }

        // Calculate consistency (Standard deviation relative index)
        if (testHistory.length > 3) {
            const avg = testHistory.reduce((a, b) => a + b, 0) / testHistory.length;
            const variance = testHistory.reduce((a, b) => a + Math.pow(b - avg, 2), 0) / testHistory.length;
            const stdDev = Math.sqrt(variance);
            const relativeStd = stdDev / (avg || 1);
            const consistency = Math.max(0, Math.min(100, Math.round((1 - relativeStd) * 100)));
            consistencyPercent.textContent = `${consistency}%`;
        }
    }

    // Ping & Jitter Phase
    async function runPingTest() {
        phaseLabel.textContent = "Ping & Jitter";
        statusBadge.textContent = "Testing";
        statusBadge.style.background = "rgba(59, 130, 246, 0.1)";
        statusBadge.style.color = "var(--color-primary)";
        progressText.textContent = "Testing server latency...";
        
        const pings = [];
        const numRequests = 5;

        for (let i = 0; i < numRequests; i++) {
            if (!isTesting) return null;
            
            const start = performance.now();
            try {
                // Rapid HEAD check with cache busting
                await fetch('/api/speedtest/download?nocache=' + Math.random(), {
                    method: 'HEAD',
                    signal: activeController.signal
                });
                const duration = performance.now() - start;
                pings.push(duration);
                metricPing.textContent = Math.round(duration);
                speedVal.textContent = Math.round(duration);
            } catch (err) {
                if (err.name === 'AbortError') return null;
                // Retry if connection drops slightly
            }
            await new Promise(r => setTimeout(r, 200));
        }

        if (pings.length === 0) return null;

        // Calculations
        const avgPing = pings.reduce((sum, val) => sum + val, 0) / pings.length;
        
        let jitterSum = 0;
        for (let i = 1; i < pings.length; i++) {
            jitterSum += Math.abs(pings[i] - pings[i - 1]);
        }
        const jitter = pings.length > 1 ? jitterSum / (pings.length - 1) : 0;

        metricPing.textContent = Math.round(avgPing);
        metricJitter.textContent = Math.round(jitter);

        return { ping: avgPing, jitter };
    }

    // Download Speed Phase
    function runDownloadTest() {
        return new Promise((resolve, reject) => {
            phaseLabel.textContent = "Download Speed";
            progressText.textContent = "Downloading speed test stream...";
            progressContainerDownload.style.display = "block";
            metricDownload.style.color = "var(--color-primary)";

            const xhr = new XMLHttpRequest();
            activeController.signal.addEventListener('abort', () => xhr.abort());

            let startTime = performance.now();
            let lastUpdate = performance.now();
            let speeds = [];
            let totalLoadedBytes = 0;

            xhr.open('GET', '/api/speedtest/download?nocache=' + Math.random(), true);

            xhr.onprogress = function (event) {
                const now = performance.now();
                const elapsedSec = (now - startTime) / 1000;
                totalLoadedBytes = event.loaded;
                
                // Track current progress bar width
                const pct = Math.min((elapsedSec * 1000 / TEST_DURATION) * 100, 100);
                progressDownload.style.width = `${pct}%`;

                if (event.loaded > 0 && elapsedSec > 0.005) {
                    // Loaded bytes to bits: event.loaded * 8
                    // Speed in Mbps: (bits / 1000000) / elapsedSec
                    const speedMbps = ((event.loaded * 8) / 1000000) / elapsedSec;
                    
                    // Throttle DOM updates
                    if (now - lastUpdate > 100) {
                        speedVal.textContent = speedMbps.toFixed(1);
                        metricDownload.textContent = speedMbps.toFixed(1);
                        updateGauge(speedMbps);
                        addSparklinePoint(speedMbps);
                        lastUpdate = now;
                    }
                    speeds.push(speedMbps);
                }

                // Hard stop test at duration threshold
                if (now - startTime >= TEST_DURATION) {
                    xhr.abort();
                }
            };

            xhr.onload = xhr.onabort = xhr.onerror = function () {
                progressDownload.style.width = "100%";
                setTimeout(() => {
                    progressContainerDownload.style.display = "none";
                }, 300);

                const finalTime = (performance.now() - startTime) / 1000;
                let finalAvg = 0;
                if (speeds.length > 0) {
                    // Compute robust average (dropping low outlier start phase values)
                    const validSpeeds = speeds.slice(Math.floor(speeds.length * 0.15));
                    finalAvg = validSpeeds.reduce((sum, val) => sum + val, 0) / (validSpeeds.length || 1);
                } else if (totalLoadedBytes > 0 && finalTime > 0) {
                    finalAvg = ((totalLoadedBytes * 8) / 1000000) / finalTime;
                }
                metricDownload.textContent = finalAvg.toFixed(1);
                resolve(finalAvg);
            };

            xhr.send();
        });
    }

    // Upload Speed Phase
    function runUploadTest() {
        return new Promise((resolve, reject) => {
            phaseLabel.textContent = "Upload Speed";
            progressText.textContent = "Uploading payload buffer...";
            progressContainerUpload.style.display = "block";
            metricUpload.style.color = "#10B981";

            // Generate random 8MB Blob payload
            const payloadSize = 8 * 1024 * 1024;
            const dataBuffer = new Uint8Array(payloadSize);
            // Pseudo-randomize buffer lightly to minimize server-side and browser compression effects
            for (let i = 0; i < dataBuffer.length; i += 1024) {
                dataBuffer[i] = Math.floor(Math.random() * 256);
            }
            const blob = new Blob([dataBuffer], { type: 'application/octet-stream' });

            const xhr = new XMLHttpRequest();
            activeController.signal.addEventListener('abort', () => xhr.abort());

            let startTime = performance.now();
            let lastUpdate = performance.now();
            let speeds = [];
            let totalUploadedBytes = 0;

            xhr.open('POST', '/api/speedtest/upload?nocache=' + Math.random(), true);

            xhr.upload.onprogress = function (event) {
                const now = performance.now();
                const elapsedSec = (now - startTime) / 1000;
                totalUploadedBytes = event.loaded;

                const pct = Math.min((elapsedSec * 1000 / TEST_DURATION) * 100, 100);
                progressUpload.style.width = `${pct}%`;

                if (event.loaded > 0 && elapsedSec > 0.005) {
                    const speedMbps = ((event.loaded * 8) / 1000000) / elapsedSec;
                    
                    if (now - lastUpdate > 100) {
                        speedVal.textContent = speedMbps.toFixed(1);
                        metricUpload.textContent = speedMbps.toFixed(1);
                        updateGauge(speedMbps);
                        addSparklinePoint(speedMbps);
                        lastUpdate = now;
                    }
                    speeds.push(speedMbps);
                }

                if (now - startTime >= TEST_DURATION) {
                    xhr.abort();
                }
            };

            xhr.onload = xhr.onabort = xhr.onerror = function () {
                progressUpload.style.width = "100%";
                setTimeout(() => {
                    progressContainerUpload.style.display = "none";
                }, 300);

                const finalTime = (performance.now() - startTime) / 1000;
                let finalAvg = 0;
                if (speeds.length > 0) {
                    const validSpeeds = speeds.slice(Math.floor(speeds.length * 0.15));
                    finalAvg = validSpeeds.reduce((sum, val) => sum + val, 0) / (validSpeeds.length || 1);
                } else if (totalUploadedBytes > 0 && finalTime > 0) {
                    finalAvg = ((totalUploadedBytes * 8) / 1000000) / finalTime;
                }
                metricUpload.textContent = finalAvg.toFixed(1);
                resolve(finalAvg);
            };

            xhr.send(blob);
        });
    }

    // Main Run Test Loop
    async function startTest() {
        if (isTesting) return;
        isTesting = true;

        // Reset UI
        startBtn.style.display = "none";
        cancelBtn.style.display = "inline-block";
        
        metricPing.textContent = "- -";
        metricJitter.textContent = "- -";
        metricDownload.textContent = "- -";
        metricUpload.textContent = "- -";
        metricDownload.style.color = "var(--color-text-primary)";
        metricUpload.style.color = "var(--color-text-primary)";
        
        speedVal.textContent = "0.0";
        updateGauge(0);
        initSparkline();

        activeController = new AbortController();

        try {
            // 1. Latency & Jitter
            const latencyResults = await runPingTest();
            if (!isTesting) return;

            // 2. Download
            const downloadSpeed = await runDownloadTest();
            if (!isTesting) return;

            // 3. Upload
            const uploadSpeed = await runUploadTest();
            if (!isTesting) return;

            // Finalize
            phaseLabel.textContent = "Completed";
            progressText.textContent = "Speed test finished successfully.";
            statusBadge.textContent = "Finished";
            statusBadge.style.background = "rgba(16, 185, 129, 0.1)";
            statusBadge.style.color = "#10B981";
            speedVal.textContent = downloadSpeed.toFixed(1);
            updateGauge(downloadSpeed);

        } catch (e) {
            console.error("Test error:", e);
            statusBadge.textContent = "Error";
            statusBadge.style.background = "rgba(239, 68, 68, 0.1)";
            statusBadge.style.color = "#EF4444";
            progressText.textContent = "Test was interrupted or failed.";
        } finally {
            isTesting = false;
            startBtn.style.display = "inline-block";
            cancelBtn.style.display = "none";
        }
    }

    function cancelTest() {
        if (activeController) {
            activeController.abort();
        }
        isTesting = false;
        phaseLabel.textContent = "Cancelled";
        progressText.textContent = "Speed test cancelled by user.";
        statusBadge.textContent = "Ready";
        statusBadge.style.background = "rgba(156, 163, 175, 0.1)";
        statusBadge.style.color = "var(--color-text-secondary)";
        speedVal.textContent = "0.0";
        updateGauge(0);
        startBtn.style.display = "inline-block";
        cancelBtn.style.display = "none";
    }

    // Event Listeners
    startBtn.addEventListener("click", startTest);
    cancelBtn.addEventListener("click", cancelTest);
});
</script>

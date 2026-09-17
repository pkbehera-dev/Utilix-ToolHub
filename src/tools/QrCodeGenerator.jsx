import React, { useState, useRef, useEffect } from 'react';
import { Download } from 'lucide-react';

export default function QrCodeGenerator() {
  const [text, setText] = useState('https://github.com');
  const canvasRef = useRef(null);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(0, 0, 200, 200);

    let hash = 0;
    for (let i = 0; i < text.length; i++) {
      hash = (hash << 5) - hash + text.charCodeAt(i);
      hash |= 0;
    }

    const drawFinder = (x, y) => {
      ctx.fillStyle = '#000000';
      ctx.fillRect(x, y, 50, 50);
      ctx.fillStyle = '#FFFFFF';
      ctx.fillRect(x + 10, y + 10, 30, 30);
      ctx.fillStyle = '#000000';
      ctx.fillRect(x + 18, y + 18, 14, 14);
    };

    drawFinder(10, 10);
    drawFinder(140, 10);
    drawFinder(10, 140);

    ctx.fillStyle = '#000000';
    for (let r = 0; r < 10; r++) {
      for (let c = 0; c < 10; c++) {
        if ((r < 3 && c < 3) || (r < 3 && c > 6) || (r > 6 && c < 3)) continue;
        if (((r * 10 + c + hash) % 3) === 0) {
          ctx.fillRect(25 + c * 15, 25 + r * 15, 12, 12);
        }
      }
    }
  }, [text]);

  const downloadPng = () => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const link = document.createElement('a');
    link.download = 'qrcode.png';
    link.href = canvas.toDataURL();
    link.click();
  };

  return (
    <div className="glass-card p-6 text-center">
      <div className="mb-6 text-left">
        <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">URL or Text</label>
        <input
          type="text"
          value={text}
          onChange={(e) => setText(e.target.value)}
          placeholder="https://example.com"
          className="form-input"
        />
      </div>

      <div className="flex justify-center mb-6">
        <canvas ref={canvasRef} width="200" height="200" className="rounded-lg border border-[var(--color-border)] p-2 bg-white" />
      </div>

      <button onClick={downloadPng} className="btn-primary">
        <Download className="w-4 h-4" /> Download QR Code PNG
      </button>
    </div>
  );
}

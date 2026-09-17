import React, { useState } from 'react';
import { Lock, Copy, Check } from 'lucide-react';

export default function PasswordHasher() {
  const [pass, setPass] = useState('');
  const [salt, setSalt] = useState('');
  const [algo, setAlgo] = useState('SHA-256');
  const [output, setOutput] = useState('');
  const [copied, setCopied] = useState(false);

  const hashPassword = async () => {
    if (!pass) { setOutput('Please enter a password'); return; }

    const encoder = new TextEncoder();
    const data = encoder.encode(pass + salt);

    if (algo === 'PBKDF2') {
      try {
        const keyMaterial = await crypto.subtle.importKey("raw", encoder.encode(pass), "PBKDF2", false, ["deriveBits"]);
        const derivedBits = await crypto.subtle.deriveBits(
          {
            name: "PBKDF2",
            salt: encoder.encode(salt || "utilix-salt"),
            iterations: 100000,
            hash: "SHA-256"
          },
          keyMaterial,
          256
        );
        const hashArray = Array.from(new Uint8Array(derivedBits));
        setOutput("$pbkdf2$sha256$100000$" + hashArray.map(b => b.toString(16).padStart(2, '0')).join(''));
      } catch (err) {
        setOutput('PBKDF2 Error: ' + err.message);
      }
    } else {
      const hashBuffer = await crypto.subtle.digest(algo, data);
      const hashArray = Array.from(new Uint8Array(hashBuffer));
      setOutput(hashArray.map(b => b.toString(16).padStart(2, '0')).join(''));
    }
  };

  const handleCopy = () => {
    if (!output) return;
    navigator.clipboard.writeText(output);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div className="glass-card p-6">
      <div className="space-y-4 mb-6">
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Password</label>
          <input
            type="password"
            value={pass}
            onChange={(e) => setPass(e.target.value)}
            placeholder="Enter password..."
            className="form-input"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Salt (Optional)</label>
          <input
            type="text"
            value={salt}
            onChange={(e) => setSalt(e.target.value)}
            placeholder="Salt string..."
            className="form-input"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Algorithm</label>
          <select value={algo} onChange={(e) => setAlgo(e.target.value)} className="form-select">
            <option value="SHA-256">SHA-256 Digest</option>
            <option value="SHA-512">SHA-512 Digest</option>
            <option value="SHA-384">SHA-384 Digest</option>
            <option value="SHA-1">SHA-1 Digest</option>
            <option value="PBKDF2">PBKDF2 (100,000 iterations)</option>
          </select>
        </div>
      </div>

      <button onClick={hashPassword} className="btn-primary mb-6">
        <Lock className="w-4 h-4" /> Generate Client-Side Hash
      </button>

      {output && (
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Generated Hash</label>
          <div className="flex gap-2">
            <textarea readOnly value={output} rows={3} className="form-textarea readonly-input font-mono text-xs" />
            <button onClick={handleCopy} className="btn-primary">
              {copied ? <Check className="w-4 h-4" /> : <Copy className="w-4 h-4" />}
            </button>
          </div>
        </div>
      )}
    </div>
  );
}

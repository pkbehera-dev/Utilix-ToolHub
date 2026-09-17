import React, { useState, useEffect } from 'react';
import { Key, Copy, Check, RotateCw } from 'lucide-react';

export default function PasswordGenerator() {
  const [length, setLength] = useState(16);
  const [includeUpper, setIncludeUpper] = useState(true);
  const [includeLower, setIncludeLower] = useState(true);
  const [includeNums, setIncludeNums] = useState(true);
  const [includeSyms, setIncludeSyms] = useState(true);
  const [password, setPassword] = useState('');
  const [copied, setCopied] = useState(false);

  const generate = () => {
    let chars = '';
    if (includeUpper) chars += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if (includeLower) chars += 'abcdefghijklmnopqrstuvwxyz';
    if (includeNums) chars += '0123456789';
    if (includeSyms) chars += '!@#$%^&*()_+-=[]{}|;:,.<>?';

    if (!chars) { setPassword('Select at least 1 option'); return; }

    let pass = '';
    const array = new Uint32Array(length);
    crypto.getRandomValues(array);
    for (let i = 0; i < length; i++) {
      pass += chars[array[i] % chars.length];
    }
    setPassword(pass);
  };

  useEffect(() => { generate(); }, [length, includeUpper, includeLower, includeNums, includeSyms]);

  const handleCopy = () => {
    if (!password) return;
    navigator.clipboard.writeText(password);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div className="glass-card p-6">
      <div className="flex gap-2 mb-6">
        <input
          type="text"
          readOnly
          value={password}
          className="form-input readonly-input font-mono text-lg text-blue-500 font-bold"
        />
        <button onClick={handleCopy} className="btn-primary">
          {copied ? <Check className="w-4 h-4" /> : <Copy className="w-4 h-4" />}
        </button>
      </div>

      <div className="mb-4">
        <div className="flex justify-between text-xs font-medium text-[var(--color-text-secondary)] mb-1">
          <span>Password Length</span>
          <span className="font-bold text-blue-500">{length} characters</span>
        </div>
        <input
          type="range"
          min="6"
          max="64"
          value={length}
          onChange={(e) => setLength(parseInt(e.target.value))}
          className="w-full"
        />
      </div>

      <div className="grid grid-cols-2 gap-3 mb-6 text-xs font-medium text-[var(--color-text-secondary)]">
        <label className="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" checked={includeUpper} onChange={(e) => setIncludeUpper(e.target.checked)} />
          Uppercase (A-Z)
        </label>
        <label className="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" checked={includeLower} onChange={(e) => setIncludeLower(e.target.checked)} />
          Lowercase (a-z)
        </label>
        <label className="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" checked={includeNums} onChange={(e) => setIncludeNums(e.target.checked)} />
          Numbers (0-9)
        </label>
        <label className="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" checked={includeSyms} onChange={(e) => setIncludeSyms(e.target.checked)} />
          Symbols (!@#$)
        </label>
      </div>

      <button onClick={generate} className="btn-primary">
        <RotateCw className="w-4 h-4" /> Generate New Password
      </button>
    </div>
  );
}

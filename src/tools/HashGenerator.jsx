import React, { useState, useEffect } from 'react';
import { Shield, Copy, Check } from 'lucide-react';

export default function HashGenerator() {
  const [input, setInput] = useState('');
  const [sha256, setSha256] = useState('');
  const [sha512, setSha512] = useState('');
  const [sha1, setSha1] = useState('');
  const [copiedField, setCopiedField] = useState(null);

  useEffect(() => {
    if (!input) {
      setSha256('');
      setSha512('');
      setSha1('');
      return;
    }

    const encoder = new TextEncoder();
    const data = encoder.encode(input);

    const compute = async () => {
      const b256 = await crypto.subtle.digest('SHA-256', data);
      const b512 = await crypto.subtle.digest('SHA-512', data);
      const b1 = await crypto.subtle.digest('SHA-1', data);

      const toHex = arr => Array.from(new Uint8Array(arr)).map(b => b.toString(16).padStart(2, '0')).join('');

      setSha256(toHex(b256));
      setSha512(toHex(b512));
      setSha1(toHex(b1));
    };

    compute();
  }, [input]);

  const copyField = (val, fieldName) => {
    if (!val) return;
    navigator.clipboard.writeText(val);
    setCopiedField(fieldName);
    setTimeout(() => setCopiedField(null), 2000);
  };

  return (
    <div className="glass-card p-6">
      <div className="mb-6">
        <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Input String</label>
        <textarea
          value={input}
          onChange={(e) => setInput(e.target.value)}
          placeholder="Enter text to generate cryptographic hash digests..."
          rows={3}
          className="form-textarea"
        />
      </div>

      <div className="space-y-4">
        <div>
          <div className="flex justify-between items-center mb-1">
            <span className="text-xs font-semibold text-[var(--color-text-primary)]">SHA-256 Digest</span>
            <button onClick={() => copyField(sha256, 'sha256')} className="text-xs text-blue-500 hover:underline flex items-center gap-1">
              {copiedField === 'sha256' ? <Check className="w-3 h-3" /> : <Copy className="w-3 h-3" />}
              {copiedField === 'sha256' ? 'Copied' : 'Copy'}
            </button>
          </div>
          <input type="text" readOnly value={sha256} className="form-input readonly-input font-mono text-xs" />
        </div>

        <div>
          <div className="flex justify-between items-center mb-1">
            <span className="text-xs font-semibold text-[var(--color-text-primary)]">SHA-512 Digest</span>
            <button onClick={() => copyField(sha512, 'sha512')} className="text-xs text-blue-500 hover:underline flex items-center gap-1">
              {copiedField === 'sha512' ? <Check className="w-3 h-3" /> : <Copy className="w-3 h-3" />}
              {copiedField === 'sha512' ? 'Copied' : 'Copy'}
            </button>
          </div>
          <input type="text" readOnly value={sha512} className="form-input readonly-input font-mono text-xs" />
        </div>

        <div>
          <div className="flex justify-between items-center mb-1">
            <span className="text-xs font-semibold text-[var(--color-text-primary)]">SHA-1 Digest</span>
            <button onClick={() => copyField(sha1, 'sha1')} className="text-xs text-blue-500 hover:underline flex items-center gap-1">
              {copiedField === 'sha1' ? <Check className="w-3 h-3" /> : <Copy className="w-3 h-3" />}
              {copiedField === 'sha1' ? 'Copied' : 'Copy'}
            </button>
          </div>
          <input type="text" readOnly value={sha1} className="form-input readonly-input font-mono text-xs" />
        </div>
      </div>
    </div>
  );
}

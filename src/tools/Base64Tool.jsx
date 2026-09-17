import React, { useState } from 'react';
import { Copy, Check, Lock, Unlock } from 'lucide-react';

export default function Base64Tool() {
  const [input, setInput] = useState('');
  const [output, setOutput] = useState('');
  const [copied, setCopied] = useState(false);

  const encode = () => {
    try {
      setOutput(btoa(unescape(encodeURIComponent(input))));
    } catch (e) {
      setOutput('Encoding Error: ' + e.message);
    }
  };

  const decode = () => {
    try {
      setOutput(decodeURIComponent(escape(atob(input.trim()))));
    } catch (e) {
      setOutput('Invalid Base64 string.');
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
      <div className="mb-4">
        <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Input Text / Base64</label>
        <textarea
          value={input}
          onChange={(e) => setInput(e.target.value)}
          placeholder="Enter text or Base64 string..."
          rows={5}
          className="form-textarea"
        />
      </div>

      <div className="flex gap-3 mb-6">
        <button onClick={encode} className="btn-primary">
          <Lock className="w-4 h-4" /> Encode Base64
        </button>
        <button onClick={decode} className="btn-secondary">
          <Unlock className="w-4 h-4" /> Decode Base64
        </button>
      </div>

      <div className="mb-4">
        <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Result Output</label>
        <textarea
          value={output}
          readOnly
          placeholder="Output will appear here..."
          rows={5}
          className="form-textarea readonly-input"
        />
      </div>

      <button onClick={handleCopy} className="btn-primary">
        {copied ? <Check className="w-4 h-4" /> : <Copy className="w-4 h-4" />}
        {copied ? 'Copied!' : 'Copy Result'}
      </button>
    </div>
  );
}

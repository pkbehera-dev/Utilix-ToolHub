import React, { useState } from 'react';
import { Copy, Check, Code, Minimize2 } from 'lucide-react';

export default function JsonFormatter() {
  const [input, setInput] = useState('');
  const [output, setOutput] = useState('');
  const [copied, setCopied] = useState(false);

  const format = () => {
    try {
      const parsed = JSON.parse(input);
      setOutput(JSON.stringify(parsed, null, 2));
    } catch (e) {
      setOutput('Invalid JSON: ' + e.message);
    }
  };

  const minify = () => {
    try {
      const parsed = JSON.parse(input);
      setOutput(JSON.stringify(parsed));
    } catch (e) {
      setOutput('Invalid JSON: ' + e.message);
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
        <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Input JSON</label>
        <textarea
          value={input}
          onChange={(e) => setInput(e.target.value)}
          placeholder='{"key": "value"}'
          rows={6}
          className="form-textarea font-mono text-xs"
        />
      </div>

      <div className="flex gap-3 mb-6">
        <button onClick={format} className="btn-primary">
          <Code className="w-4 h-4" /> Format / Beautify
        </button>
        <button onClick={minify} className="btn-secondary">
          <Minimize2 className="w-4 h-4" /> Minify
        </button>
      </div>

      <div className="mb-4">
        <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Formatted Output</label>
        <textarea
          value={output}
          readOnly
          rows={8}
          className="form-textarea readonly-input font-mono text-xs"
        />
      </div>

      <button onClick={handleCopy} className="btn-primary">
        {copied ? <Check className="w-4 h-4" /> : <Copy className="w-4 h-4" />}
        {copied ? 'Copied!' : 'Copy Result'}
      </button>
    </div>
  );
}

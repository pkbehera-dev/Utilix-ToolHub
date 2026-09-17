import React, { useState } from 'react';
import { Copy, Check, Filter } from 'lucide-react';

export default function RemoveDuplicates() {
  const [input, setInput] = useState('');
  const [output, setOutput] = useState('');
  const [caseSensitive, setCaseSensitive] = useState(true);
  const [trimLines, setTrimLines] = useState(true);
  const [removeEmpty, setRemoveEmpty] = useState(true);
  const [copied, setCopied] = useState(false);

  const process = () => {
    const lines = input.split('\n');
    const seen = new Set();
    const result = [];

    lines.forEach(line => {
      let processed = trimLines ? line.trim() : line;
      if (removeEmpty && processed === '') return;
      let key = caseSensitive ? processed : processed.toLowerCase();
      if (!seen.has(key)) {
        seen.add(key);
        result.push(processed);
      }
    });

    setOutput(result.join('\n'));
  };

  const handleCopy = () => {
    if (!output) return;
    navigator.clipboard.writeText(output);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div className="glass-card p-6">
      <textarea
        value={input}
        onChange={(e) => setInput(e.target.value)}
        placeholder="Paste text containing duplicate lines..."
        rows={6}
        className="form-textarea mb-4"
      />

      <div className="flex flex-wrap gap-4 text-xs font-medium mb-4 text-[var(--color-text-secondary)]">
        <label className="flex items-center gap-1.5 cursor-pointer">
          <input type="checkbox" checked={caseSensitive} onChange={(e) => setCaseSensitive(e.target.checked)} />
          Case Sensitive
        </label>
        <label className="flex items-center gap-1.5 cursor-pointer">
          <input type="checkbox" checked={trimLines} onChange={(e) => setTrimLines(e.target.checked)} />
          Trim Whitespace
        </label>
        <label className="flex items-center gap-1.5 cursor-pointer">
          <input type="checkbox" checked={removeEmpty} onChange={(e) => setRemoveEmpty(e.target.checked)} />
          Remove Empty Lines
        </label>
      </div>

      <button onClick={process} className="btn-primary mb-6">
        <Filter className="w-4 h-4" /> Deduplicate Lines
      </button>

      <div className="mb-4">
        <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">
          Output ({output ? output.split('\n').length : 0} lines)
        </label>
        <textarea
          value={output}
          readOnly
          rows={6}
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

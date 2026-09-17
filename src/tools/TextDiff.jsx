import React, { useState } from 'react';
import { GitCompare } from 'lucide-react';

export default function TextDiff() {
  const [original, setOriginal] = useState('');
  const [modified, setModified] = useState('');
  const [diffResult, setDiffResult] = useState(null);

  const compare = () => {
    const origLines = original.split('\n');
    const modLines = modified.split('\n');
    const max = Math.max(origLines.length, modLines.length);

    const result = [];
    for (let i = 0; i < max; i++) {
      const line1 = origLines[i];
      const line2 = modLines[i];
      if (line1 === line2) {
        result.push({ type: 'same', text: line1 || '', line: i + 1 });
      } else {
        if (line1 !== undefined) result.push({ type: 'removed', text: line1, line: '-' });
        if (line2 !== undefined) result.push({ type: 'added', text: line2, line: '+' });
      }
    }
    setDiffResult(result);
  };

  return (
    <div className="glass-card p-6">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Original Text</label>
          <textarea
            value={original}
            onChange={(e) => setOriginal(e.target.value)}
            placeholder="Paste original text..."
            rows={7}
            className="form-textarea"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Modified Text</label>
          <textarea
            value={modified}
            onChange={(e) => setModified(e.target.value)}
            placeholder="Paste modified text..."
            rows={7}
            className="form-textarea"
          />
        </div>
      </div>

      <button onClick={compare} className="btn-primary mb-6">
        <GitCompare className="w-4 h-4" /> Compare Differences
      </button>

      {diffResult && (
        <div className="p-4 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] font-mono text-xs overflow-x-auto max-h-96">
          {diffResult.map((item, idx) => (
            <div
              key={idx}
              className={`flex items-center px-2 py-0.5 whitespace-pre ${
                item.type === 'added' ? 'bg-emerald-500/10 text-emerald-500' :
                item.type === 'removed' ? 'bg-rose-500/10 text-rose-500' : 'text-[var(--color-text-secondary)]'
              }`}
            >
              <span className="w-8 select-none opacity-50">{item.line}</span>
              <span>{item.text}</span>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

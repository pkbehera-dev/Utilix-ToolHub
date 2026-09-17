import React, { useState } from 'react';
import { Shuffle } from 'lucide-react';

export default function RandomNumberGen() {
  const [min, setMin] = useState(1);
  const [max, setMax] = useState(100);
  const [count, setCount] = useState(1);
  const [results, setResults] = useState([]);

  const generate = () => {
    if (min >= max) return;
    const res = [];
    for (let i = 0; i < count; i++) {
      res.push(Math.floor(Math.random() * (max - min + 1)) + min);
    }
    setResults(res);
  };

  return (
    <div className="glass-card p-6">
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Min Value</label>
          <input type="number" value={min} onChange={(e) => setMin(parseInt(e.target.value) || 0)} className="form-input" />
        </div>
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Max Value</label>
          <input type="number" value={max} onChange={(e) => setMax(parseInt(e.target.value) || 0)} className="form-input" />
        </div>
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Quantity</label>
          <input type="number" min="1" max="100" value={count} onChange={(e) => setCount(Math.max(1, parseInt(e.target.value) || 1))} className="form-input" />
        </div>
      </div>

      <button onClick={generate} className="btn-primary mb-6">
        <Shuffle className="w-4 h-4" /> Generate Number(s)
      </button>

      {results.length > 0 && (
        <div className="p-6 rounded-xl bg-[var(--color-background)] border border-[var(--color-border)] text-center">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">Results</span>
          <span className="text-3xl font-extrabold text-blue-500 break-words">{results.join(', ')}</span>
        </div>
      )}
    </div>
  );
}

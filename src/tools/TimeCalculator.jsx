import React, { useState } from 'react';
import { Clock } from 'lucide-react';

export default function TimeCalculator() {
  const [start, setStart] = useState('');
  const [end, setEnd] = useState('');
  const [diff, setDiff] = useState(null);

  const calculate = () => {
    if (!start || !end) return;
    const sDate = new Date(start);
    const eDate = new Date(end);
    let diffMs = Math.abs(eDate - sDate);

    const hours = Math.floor(diffMs / (1000 * 60 * 60));
    diffMs -= hours * (1000 * 60 * 60);
    const minutes = Math.floor(diffMs / (1000 * 60));

    setDiff({ hours, minutes });
  };

  return (
    <div className="glass-card p-6">
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Start Date & Time</label>
          <input
            type="datetime-local"
            value={start}
            onChange={(e) => setStart(e.target.value)}
            className="form-input"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">End Date & Time</label>
          <input
            type="datetime-local"
            value={end}
            onChange={(e) => setEnd(e.target.value)}
            className="form-input"
          />
        </div>
      </div>

      <button onClick={calculate} className="btn-primary mb-6">
        <Clock className="w-4 h-4" /> Calculate Duration
      </button>

      {diff && (
        <div className="p-6 rounded-xl bg-[var(--color-background)] border border-[var(--color-border)] text-center">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">Duration Difference</span>
          <span className="text-2xl font-bold text-blue-500">{diff.hours} Hours, {diff.minutes} Minutes</span>
        </div>
      )}
    </div>
  );
}

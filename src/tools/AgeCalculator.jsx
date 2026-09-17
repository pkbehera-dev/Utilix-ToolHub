import React, { useState } from 'react';
import { Calendar } from 'lucide-react';

export default function AgeCalculator() {
  const [dob, setDob] = useState('');
  const [result, setResult] = useState(null);

  const calculate = () => {
    if (!dob) return;
    const birthDate = new Date(dob);
    const today = new Date();

    let years = today.getFullYear() - birthDate.getFullYear();
    let months = today.getMonth() - birthDate.getMonth();
    let days = today.getDate() - birthDate.getDate();

    if (days < 0) {
      months--;
      const prevMonth = new Date(today.getFullYear(), today.getMonth(), 0);
      days += prevMonth.getDate();
    }
    if (months < 0) {
      years--;
      months += 12;
    }

    const totalHours = Math.floor((today - birthDate) / (1000 * 60 * 60));

    setResult({ years, months, days, hours: totalHours });
  };

  return (
    <div className="glass-card p-6">
      <div className="mb-4">
        <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Date of Birth</label>
        <input
          type="date"
          value={dob}
          onChange={(e) => setDob(e.target.value)}
          className="form-input"
        />
      </div>

      <button onClick={calculate} className="btn-primary mb-6">
        <Calendar className="w-4 h-4" /> Calculate Age
      </button>

      {result && (
        <div className="grid grid-cols-2 sm:grid-cols-4 gap-3">
          <div className="p-4 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] text-center">
            <span className="block text-xs text-[var(--color-text-secondary)] mb-1 font-medium">Years</span>
            <span className="text-2xl font-bold text-blue-500">{result.years}</span>
          </div>
          <div className="p-4 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] text-center">
            <span className="block text-xs text-[var(--color-text-secondary)] mb-1 font-medium">Months</span>
            <span className="text-2xl font-bold text-blue-500">{result.months}</span>
          </div>
          <div className="p-4 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] text-center">
            <span className="block text-xs text-[var(--color-text-secondary)] mb-1 font-medium">Days</span>
            <span className="text-2xl font-bold text-blue-500">{result.days}</span>
          </div>
          <div className="p-4 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] text-center">
            <span className="block text-xs text-[var(--color-text-secondary)] mb-1 font-medium">Total Hours</span>
            <span className="text-2xl font-bold text-blue-500">{result.hours.toLocaleString()}</span>
          </div>
        </div>
      )}
    </div>
  );
}

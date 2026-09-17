import React, { useState } from 'react';
import { Percent } from 'lucide-react';

export default function GstCalculator() {
  const [amount, setAmount] = useState(1000);
  const [rate, setRate] = useState(18);
  const [type, setType] = useState('exclusive');

  let net = 0;
  let tax = 0;
  let gross = 0;

  if (type === 'exclusive') {
    net = amount;
    tax = (amount * rate) / 100;
    gross = net + tax;
  } else {
    gross = amount;
    tax = amount - (amount * (100 / (100 + rate)));
    net = gross - tax;
  }

  return (
    <div className="glass-card p-6">
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Amount ($ / ₹)</label>
          <input
            type="number"
            value={amount}
            onChange={(e) => setAmount(parseFloat(e.target.value) || 0)}
            className="form-input"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">GST Rate (%)</label>
          <input
            type="number"
            value={rate}
            onChange={(e) => setRate(parseFloat(e.target.value) || 0)}
            className="form-input"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Tax Mode</label>
          <select value={type} onChange={(e) => setType(e.target.value)} className="form-select">
            <option value="exclusive">Exclusive (Add GST)</option>
            <option value="inclusive">Inclusive (Remove GST)</option>
          </select>
        </div>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div className="p-4 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] text-center">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">Net Amount</span>
          <span className="text-xl font-bold text-blue-500">${net.toFixed(2)}</span>
        </div>
        <div className="p-4 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] text-center">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">GST Tax Amount</span>
          <span className="text-xl font-bold text-emerald-500">${tax.toFixed(2)}</span>
        </div>
        <div className="p-4 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] text-center">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">Gross Amount</span>
          <span className="text-xl font-bold text-blue-500">${gross.toFixed(2)}</span>
        </div>
      </div>
    </div>
  );
}

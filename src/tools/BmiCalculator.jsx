import React, { useState } from 'react';
import { Activity } from 'lucide-react';

export default function BmiCalculator() {
  const [weight, setWeight] = useState(70);
  const [height, setHeight] = useState(175);

  const hMeter = height / 100;
  const bmi = (weight && height > 0) ? (weight / (hMeter * hMeter)).toFixed(1) : 0;

  const getStatus = (val) => {
    if (val < 18.5) return { label: "Underweight", color: "text-amber-500" };
    if (val < 25) return { label: "Normal weight", color: "text-emerald-500" };
    if (val < 30) return { label: "Overweight", color: "text-amber-500" };
    return { label: "Obese", color: "text-rose-500" };
  };

  const status = getStatus(bmi);

  return (
    <div className="glass-card p-6">
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Weight (kg)</label>
          <input
            type="number"
            value={weight}
            onChange={(e) => setWeight(parseFloat(e.target.value) || 0)}
            className="form-input"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Height (cm)</label>
          <input
            type="number"
            value={height}
            onChange={(e) => setHeight(parseFloat(e.target.value) || 0)}
            className="form-input"
          />
        </div>
      </div>

      <div className="p-6 rounded-xl bg-[var(--color-background)] border border-[var(--color-border)] text-center">
        <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">Your BMI Score</span>
        <span className="text-4xl font-extrabold text-blue-500 block mb-2">{bmi}</span>
        <span className={`text-base font-semibold ${status.color}`}>{status.label}</span>
      </div>
    </div>
  );
}

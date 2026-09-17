import React, { useState } from 'react';
import { Dices } from 'lucide-react';

export default function DiceRoller() {
  const [sides, setSides] = useState(6);
  const [count, setCount] = useState(1);
  const [rolls, setRolls] = useState([]);
  const [score, setScore] = useState(0);

  const roll = () => {
    const newRolls = [];
    let total = 0;
    for (let i = 0; i < count; i++) {
      const val = Math.floor(Math.random() * sides) + 1;
      newRolls.push(val);
      total += val;
    }
    setRolls(newRolls);
    setScore(total);
  };

  return (
    <div className="glass-card p-6 text-center">
      <div className="grid grid-cols-2 gap-4 max-w-sm mx-auto mb-6">
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Dice Type</label>
          <select value={sides} onChange={(e) => setSides(parseInt(e.target.value))} className="form-select">
            <option value={6}>D6 (6 Sided)</option>
            <option value={4}>D4 (4 Sided)</option>
            <option value={8}>D8 (8 Sided)</option>
            <option value={10}>D10 (10 Sided)</option>
            <option value={12}>D12 (12 Sided)</option>
            <option value={20}>D20 (20 Sided)</option>
          </select>
        </div>
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Count</label>
          <input
            type="number"
            min="1"
            max="10"
            value={count}
            onChange={(e) => setCount(Math.max(1, parseInt(e.target.value) || 1))}
            className="form-input text-center"
          />
        </div>
      </div>

      <button onClick={roll} className="btn-primary mb-6">
        <Dices className="w-4 h-4" /> Roll Dice
      </button>

      {score > 0 && (
        <div className="p-6 rounded-xl bg-[var(--color-background)] border border-[var(--color-border)]">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">Total Score</span>
          <span className="text-4xl font-extrabold text-blue-500 block mb-2">{score}</span>
          <span className="text-xs text-[var(--color-text-secondary)]">Roll Breakdown: [{rolls.join(', ')}]</span>
        </div>
      )}
    </div>
  );
}

import React, { useState } from 'react';
import { Coins } from 'lucide-react';

export default function CoinFlip() {
  const [result, setResult] = useState('HEADS');
  const [flipping, setFlipping] = useState(false);
  const [headsCount, setHeadsCount] = useState(0);
  const [tailsCount, setTailsCount] = useState(0);

  const flip = () => {
    setFlipping(true);
    setTimeout(() => {
      const isHeads = Math.random() > 0.5;
      if (isHeads) {
        setResult('HEADS');
        setHeadsCount(prev => prev + 1);
      } else {
        setResult('TAILS');
        setTailsCount(prev => prev + 1);
      }
      setFlipping(false);
    }, 500);
  };

  return (
    <div className="glass-card p-6 text-center">
      <div className="h-32 flex items-center justify-center my-4">
        <div
          className={`w-28 h-28 rounded-full border-4 flex items-center justify-center text-xl font-black transition-transform duration-500 shadow-xl ${
            flipping ? 'rotate-[720deg] scale-110' : ''
          } ${
            result === 'HEADS'
              ? 'bg-amber-400 border-amber-600 text-amber-950'
              : 'bg-slate-300 border-slate-500 text-slate-800'
          }`}
        >
          {result}
        </div>
      </div>

      <button onClick={flip} disabled={flipping} className="btn-primary mb-6">
        <Coins className="w-4 h-4" /> Flip Coin
      </button>

      <div className="grid grid-cols-2 gap-4 max-w-sm mx-auto">
        <div className="p-3 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)]">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">Heads Tally</span>
          <span className="text-xl font-bold text-amber-500">{headsCount}</span>
        </div>
        <div className="p-3 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)]">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">Tails Tally</span>
          <span className="text-xl font-bold text-slate-400">{tailsCount}</span>
        </div>
      </div>
    </div>
  );
}

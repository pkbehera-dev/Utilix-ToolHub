import React, { useState, useEffect } from 'react';
import { Play, Pause, RotateCcw } from 'lucide-react';

export default function PomodoroTimer() {
  const [secondsLeft, setSecondsLeft] = useState(25 * 60);
  const [isRunning, setIsRunning] = useState(false);
  const [isWorking, setIsWorking] = useState(true);

  useEffect(() => {
    let interval = null;
    if (isRunning) {
      interval = setInterval(() => {
        setSecondsLeft(prev => {
          if (prev > 1) return prev - 1;
          setIsRunning(false);
          const nextWorking = !isWorking;
          setIsWorking(nextWorking);
          alert(nextWorking ? "Break finished! Back to work." : "Work session done! Take a break.");
          return nextWorking ? 25 * 60 : 5 * 60;
        });
      }, 1000);
    }
    return () => clearInterval(interval);
  }, [isRunning, isWorking]);

  const m = String(Math.floor(secondsLeft / 60)).padStart(2, '0');
  const s = String(secondsLeft % 60).padStart(2, '0');

  const reset = () => {
    setIsRunning(false);
    setIsWorking(true);
    setSecondsLeft(25 * 60);
  };

  return (
    <div className="glass-card p-6 text-center">
      <h3 className="text-lg font-bold mb-2 text-[var(--color-text-primary)]">
        {isWorking ? "Work Focus Session (25m)" : "Rest Break Session (5m)"}
      </h3>

      <div className="my-6">
        <span className="text-6xl font-black text-blue-500 font-mono tracking-tight">{m}:{s}</span>
      </div>

      <div className="flex justify-center gap-3">
        {!isRunning ? (
          <button onClick={() => setIsRunning(true)} className="btn-primary">
            <Play className="w-4 h-4" /> Start
          </button>
        ) : (
          <button onClick={() => setIsRunning(false)} className="btn-secondary">
            <Pause className="w-4 h-4" /> Pause
          </button>
        )}
        <button onClick={reset} className="btn-secondary">
          <RotateCcw className="w-4 h-4" /> Reset
        </button>
      </div>
    </div>
  );
}

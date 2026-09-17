import React, { useState, useEffect } from 'react';
import { Play, Pause, RotateCcw } from 'lucide-react';

export default function Timer() {
  const [hours, setHours] = useState(0);
  const [minutes, setMinutes] = useState(5);
  const [seconds, setSeconds] = useState(0);
  const [secondsLeft, setSecondsLeft] = useState(300);
  const [isRunning, setIsRunning] = useState(false);

  useEffect(() => {
    let interval = null;
    if (isRunning) {
      interval = setInterval(() => {
        setSecondsLeft(prev => {
          if (prev > 1) return prev - 1;
          setIsRunning(false);
          alert("Time is up!");
          return 0;
        });
      }, 1000);
    }
    return () => clearInterval(interval);
  }, [isRunning]);

  const startTimer = () => {
    if (!isRunning && secondsLeft === 0) {
      const total = hours * 3600 + minutes * 60 + seconds;
      setSecondsLeft(total);
    }
    setIsRunning(true);
  };

  const reset = () => {
    setIsRunning(false);
    const total = hours * 3600 + minutes * 60 + seconds;
    setSecondsLeft(total);
  };

  const h = String(Math.floor(secondsLeft / 3600)).padStart(2, '0');
  const m = String(Math.floor((secondsLeft % 3600) / 60)).padStart(2, '0');
  const s = String(secondsLeft % 60).padStart(2, '0');

  return (
    <div className="glass-card p-6 text-center">
      <div className="grid grid-cols-3 gap-3 max-w-xs mx-auto mb-4">
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Hours</label>
          <input type="number" min="0" value={hours} onChange={(e) => setHours(parseInt(e.target.value) || 0)} className="form-input text-center" />
        </div>
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Minutes</label>
          <input type="number" min="0" max="59" value={minutes} onChange={(e) => setMinutes(parseInt(e.target.value) || 0)} className="form-input text-center" />
        </div>
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Seconds</label>
          <input type="number" min="0" max="59" value={seconds} onChange={(e) => setSeconds(parseInt(e.target.value) || 0)} className="form-input text-center" />
        </div>
      </div>

      <div className="my-6">
        <span className="text-5xl font-black text-blue-500 font-mono tracking-tight">{h}:{m}:{s}</span>
      </div>

      <div className="flex justify-center gap-3">
        {!isRunning ? (
          <button onClick={startTimer} className="btn-primary">
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

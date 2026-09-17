import React, { useState, useEffect } from 'react';
import { Play, Pause, RotateCcw, Flag } from 'lucide-react';

export default function Stopwatch() {
  const [elapsed, setElapsed] = useState(0);
  const [isRunning, setIsRunning] = useState(false);
  const [laps, setLaps] = useState([]);

  useEffect(() => {
    let interval = null;
    if (isRunning) {
      const startTime = Date.now() - elapsed;
      interval = setInterval(() => {
        setElapsed(Date.now() - startTime);
      }, 10);
    }
    return () => clearInterval(interval);
  }, [isRunning]);

  const format = (ms) => {
    const h = String(Math.floor(ms / 3600000)).padStart(2, '0');
    const m = String(Math.floor((ms % 3600000) / 60000)).padStart(2, '0');
    const s = String(Math.floor((ms % 60000) / 1000)).padStart(2, '0');
    const cs = String(Math.floor((ms % 1000) / 10)).padStart(2, '0');
    return `${h}:${m}:${s}.${cs}`;
  };

  const addLap = () => {
    if (isRunning) {
      setLaps(prev => [{ num: prev.length + 1, time: format(elapsed) }, ...prev]);
    }
  };

  const reset = () => {
    setIsRunning(false);
    setElapsed(0);
    setLaps([]);
  };

  return (
    <div className="glass-card p-6 text-center">
      <div className="my-6">
        <span className="text-5xl font-black text-blue-500 font-mono tracking-tight">{format(elapsed)}</span>
      </div>

      <div className="flex justify-center gap-3 mb-6">
        {!isRunning ? (
          <button onClick={() => setIsRunning(true)} className="btn-primary">
            <Play className="w-4 h-4" /> Start
          </button>
        ) : (
          <button onClick={() => setIsRunning(false)} className="btn-secondary">
            <Pause className="w-4 h-4" /> Stop
          </button>
        )}
        <button onClick={addLap} disabled={!isRunning} className="btn-secondary">
          <Flag className="w-4 h-4" /> Lap
        </button>
        <button onClick={reset} className="btn-secondary">
          <RotateCcw className="w-4 h-4" /> Reset
        </button>
      </div>

      {laps.length > 0 && (
        <div className="max-h-48 overflow-y-auto border border-[var(--color-border)] rounded-lg p-2 divide-y divide-[var(--color-border)] text-xs text-left">
          {laps.map(lap => (
            <div key={lap.num} className="flex justify-between py-1.5 px-3">
              <span className="font-semibold text-[var(--color-text-secondary)]">Lap {lap.num}</span>
              <span className="font-mono text-blue-500 font-bold">{lap.time}</span>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

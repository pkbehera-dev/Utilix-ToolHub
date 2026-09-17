import React, { useState, useEffect } from 'react';
import { Layers, Search, Sun, Moon, Clock } from 'lucide-react';

export default function Header({ onOpenSearch, theme, toggleTheme }) {
  const [timeStr, setTimeStr] = useState('');
  const [dateStr, setDateStr] = useState('');

  useEffect(() => {
    const updateTime = () => {
      const now = new Date();
      setTimeStr(now.toLocaleTimeString('en-US', { hour12: false }));
      setDateStr(now.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }));
    };
    updateTime();
    const interval = setInterval(updateTime, 1000);
    return () => clearInterval(interval);
  }, []);

  return (
    <header className="sticky top-0 z-40 backdrop-blur-md bg-[var(--color-background)]/80 border-b border-[var(--color-border)] py-3 px-4 transition-colors">
      <div className="max-w-6xl mx-auto flex items-center justify-between gap-4">
        {/* Logo */}
        <a href="#/" className="flex items-center gap-2.5 font-bold text-xl text-[var(--color-text-primary)] hover:opacity-90">
          <div className="p-2 rounded-lg bg-blue-500/10 text-blue-500">
            <Layers className="w-5 h-5" />
          </div>
          <span>Utilix ToolHub</span>
        </a>

        {/* Global Search / Command Palette trigger */}
        <button
          onClick={onOpenSearch}
          className="hidden md:flex items-center gap-2.5 px-3.5 py-1.5 rounded-lg bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-text-secondary)] text-sm hover:border-blue-500 transition-colors cursor-pointer w-64 justify-between"
        >
          <div className="flex items-center gap-2">
            <Search className="w-4 h-4 text-[var(--color-text-secondary)]" />
            <span>Search tools...</span>
          </div>
          <kbd className="px-1.5 py-0.5 text-[10px] font-semibold bg-[var(--color-background)] border border-[var(--color-border)] rounded text-[var(--color-text-secondary)]">
            Ctrl K
          </kbd>
        </button>

        <div className="flex items-center gap-3">
          {/* Live Clock */}
          <div className="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[var(--color-surface)] border border-[var(--color-border)] text-xs font-mono text-[var(--color-text-secondary)]">
            <Clock className="w-3.5 h-3.5 text-blue-500" />
            <span>{timeStr}</span>
            <span className="opacity-40">|</span>
            <span>{dateStr}</span>
          </div>

          {/* Theme Toggle */}
          <button
            onClick={toggleTheme}
            className="p-2 rounded-lg bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-text-primary)] hover:border-blue-500 transition-colors cursor-pointer"
            aria-label="Toggle Theme"
          >
            {theme === 'dark' ? <Sun className="w-4 h-4 text-amber-400" /> : <Moon className="w-4 h-4 text-slate-700" />}
          </button>

          {/* Mobile Search Button */}
          <button
            onClick={onOpenSearch}
            className="md:hidden p-2 rounded-lg bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-text-primary)]"
            aria-label="Search"
          >
            <Search className="w-4 h-4" />
          </button>
        </div>
      </div>
    </header>
  );
}

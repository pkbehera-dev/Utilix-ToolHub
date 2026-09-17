import React from 'react';
import { Layers } from 'lucide-react';

export default function Footer() {
  return (
    <footer className="border-t border-[var(--color-border)] py-8 px-4 bg-[var(--color-surface)]/40 mt-auto">
      <div className="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-[var(--color-text-secondary)]">
        <div className="flex items-center gap-2">
          <Layers className="w-4 h-4 text-blue-500" />
          <span className="font-semibold text-[var(--color-text-primary)]">Utilix ToolHub</span>
          <span>&copy; {new Date().getFullYear()} • Online Utilities</span>
        </div>

        <div className="flex items-center gap-6 text-xs">
          <a href="#/about" className="hover:text-blue-500 transition-colors">About</a>
          <a href="#/privacy" className="hover:text-blue-500 transition-colors">Privacy Policy</a>
          <a href="#/terms" className="hover:text-blue-500 transition-colors">Terms of Service</a>
        </div>
      </div>
    </footer>
  );
}

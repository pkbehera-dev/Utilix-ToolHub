import React from 'react';

export default function Terms() {
  return (
    <div className="max-w-3xl mx-auto py-8 space-y-6 animate-fade-in">
      <h1 className="text-3xl font-extrabold text-[var(--color-text-primary)]">Terms of Service</h1>

      <div className="glass-card p-6 space-y-4 text-sm text-[var(--color-text-secondary)] leading-relaxed">
        <h3 className="font-bold text-base text-[var(--color-text-primary)]">1. Usage Agreement</h3>
        <p>
          Utilix ToolHub provides free online utility tools "as is" without warranty of any kind. You may use these utilities for personal, educational, or commercial purposes.
        </p>

        <h3 className="font-bold text-base text-[var(--color-text-primary)]">2. Disclaimer of Liability</h3>
        <p>
          In no event shall the authors or copyright holders be liable for any claims, damages, or liabilities arising from the use of tools available on this website.
        </p>
      </div>
    </div>
  );
}

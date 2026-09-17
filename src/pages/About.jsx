import React from 'react';
import { Layers, ShieldCheck, Zap, Globe } from 'lucide-react';

export default function About() {
  return (
    <div className="max-w-3xl mx-auto py-8 space-y-8 animate-fade-in">
      <div className="text-center space-y-3">
        <h1 className="text-3xl font-extrabold text-[var(--color-text-primary)]">About Utilix ToolHub</h1>
        <p className="text-sm text-[var(--color-text-secondary)]">Your modern, serverless digital utility suite.</p>
      </div>

      <div className="glass-card p-6 space-y-6 text-sm text-[var(--color-text-secondary)] leading-relaxed">
        <p>
          <strong>Utilix ToolHub</strong> is an open-source, client-side digital utility platform built for developers, designers, students, and everyday web users.
        </p>

        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
          <div className="p-4 rounded-xl bg-[var(--color-background)] border border-[var(--color-border)]">
            <Zap className="w-5 h-5 text-blue-500 mb-2" />
            <h4 className="font-bold text-[var(--color-text-primary)] mb-1">Instant Speed</h4>
            <p className="text-xs">Executed 100% in your browser with zero network latency.</p>
          </div>
          <div className="p-4 rounded-xl bg-[var(--color-background)] border border-[var(--color-border)]">
            <ShieldCheck className="w-5 h-5 text-emerald-500 mb-2" />
            <h4 className="font-bold text-[var(--color-text-primary)] mb-1">Private & Secure</h4>
            <p className="text-xs">No data is sent to external servers or stored in cloud databases.</p>
          </div>
        </div>
      </div>
    </div>
  );
}

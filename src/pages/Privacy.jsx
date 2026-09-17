import React from 'react';

export default function Privacy() {
  return (
    <div className="max-w-3xl mx-auto py-8 space-y-6 animate-fade-in">
      <h1 className="text-3xl font-extrabold text-[var(--color-text-primary)]">Privacy Policy</h1>
      
      <div className="glass-card p-6 space-y-4 text-sm text-[var(--color-text-secondary)] leading-relaxed">
        <h3 className="font-bold text-base text-[var(--color-text-primary)]">1. Data Collection</h3>
        <p>
          Utilix ToolHub operates 100% on the client-side. We do not collect, store, transmit, or process your personal data or input text on any external server.
        </p>

        <h3 className="font-bold text-base text-[var(--color-text-primary)]">2. Local Storage</h3>
        <p>
          Preferences (such as your active dark/light mode setting) are saved locally in your browser's LocalStorage for convenience and are never shared.
        </p>

        <h3 className="font-bold text-base text-[var(--color-text-primary)]">3. Security</h3>
        <p>
          Cryptographic functions and tools (such as hash generation and password hashing) rely on native browser APIs (such as the Web Crypto API) to guarantee maximum security.
        </p>
      </div>
    </div>
  );
}

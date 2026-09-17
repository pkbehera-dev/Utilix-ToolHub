import React, { useState } from 'react';
import { Copy, Check, Terminal } from 'lucide-react';

const FONT_MAP = {
  'A': ["  ▲  ", " / \\ ", "/___\\", "|   |"],
  'B': ["___ ", "|  \\", "|--<", "|__/"],
  'C': [" ___", "/   ", "|   ", "\\___"],
  'D': ["___ ", "|  \\", "|  |", "|__/"],
  'E': ["____", "|___", "|___", "|___"],
  'F': ["____", "|___", "|___", "|   "],
  'G': [" ___", "/   ", "| _ ", "\\__/"],
  'H': ["_  _", "|__|", "|  |", "|  |"],
  'I': ["___", " | ", " | ", "___"],
  'J': [" ___", "  | ", "  | ", "\\__/"],
  'K': ["_  _", "|_/ ", "| \\ ", "|  \\"],
  'L': ["_   ", "|   ", "|   ", "|___"],
  'M': ["_  _", "|\\/|", "|  |", "|  |"],
  'N': ["_  _", "|\\ |", "| \\|", "|  |"],
  'O': [" ___", "/   \\", "|   |", "\\___/"],
  'P': ["___ ", "|  |", "|--'", "|   "],
  'Q': [" ___", "/   \\", "|  \\|", "\\___\\"],
  'R': ["___ ", "|  |", "|--'", "|  \\"],
  'S': [" ___", "/ __", " __\\", "\\___"],
  'T': ["___", " | ", " | ", " | "],
  'U': ["_  _", "|  |", "|  |", "\\__/"],
  'V': ["_  _", "|  |", "\\  /", " \\/ "],
  'W': ["_  _", "|  |", "|\\/|", "|  |"],
  'X': ["_  _", "\\  /", " >< ", "/  \\"],
  'Y': ["_  _", "\\  /", "  | ", "  | "],
  'Z': ["___", "  / ", " /  ", "___ "]
};

export default function AsciiArt() {
  const [input, setInput] = useState('UTILIX');
  const [copied, setCopied] = useState(false);

  const generateAscii = () => {
    const text = input.toUpperCase().replace(/[^A-Z ]/g, '');
    if (!text) return 'Enter a word above.';
    const lines = ["", "", "", ""];
    for (const char of text) {
      if (char === ' ') {
        for (let i = 0; i < 4; i++) lines[i] += "   ";
      } else if (FONT_MAP[char]) {
        for (let i = 0; i < 4; i++) lines[i] += FONT_MAP[char][i] + " ";
      }
    }
    return lines.join('\n');
  };

  const asciiResult = generateAscii();

  const handleCopy = () => {
    navigator.clipboard.writeText(asciiResult);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div className="glass-card p-6">
      <div className="mb-4">
        <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Enter Word (A-Z)</label>
        <input
          type="text"
          value={input}
          onChange={(e) => setInput(e.target.value)}
          placeholder="UTILIX"
          className="form-input uppercase font-mono"
        />
      </div>

      <div className="mb-6">
        <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">ASCII Banner Output</label>
        <pre className="p-4 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] font-mono text-xs overflow-x-auto text-blue-500 font-bold">
          {asciiResult}
        </pre>
      </div>

      <button onClick={handleCopy} className="btn-primary">
        {copied ? <Check className="w-4 h-4" /> : <Copy className="w-4 h-4" />}
        {copied ? 'Copied!' : 'Copy Banner'}
      </button>
    </div>
  );
}

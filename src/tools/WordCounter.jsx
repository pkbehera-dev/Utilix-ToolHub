import React, { useState } from 'react';
import { Copy, Trash2, Check } from 'lucide-react';

export default function WordCounter() {
  const [text, setText] = useState('');
  const [copied, setCopied] = useState(false);

  const wordCount = text.trim() ? text.trim().split(/\s+/).length : 0;
  const charCount = text.length;
  const charNoSpaceCount = text.replace(/\s+/g, '').length;
  const sentenceCount = text.split(/[.!?]+/).filter(s => s.trim().length > 0).length;
  const paragraphCount = text.split(/\n+/).filter(p => p.trim().length > 0).length;
  const readingTime = Math.ceil(wordCount / 200);

  const handleCopy = () => {
    if (!text) return;
    navigator.clipboard.writeText(text);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div className="glass-card p-6">
      <textarea
        value={text}
        onChange={(e) => setText(e.target.value)}
        placeholder="Type or paste your text here to analyze..."
        rows={8}
        className="form-textarea mb-6 resize-y"
      />

      <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 mb-6">
        <div className="p-3 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] text-center">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">Words</span>
          <span className="text-xl font-bold text-blue-500">{wordCount}</span>
        </div>
        <div className="p-3 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] text-center">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">Characters</span>
          <span className="text-xl font-bold text-blue-500">{charCount}</span>
        </div>
        <div className="p-3 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] text-center">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">No Spaces</span>
          <span className="text-xl font-bold text-blue-500">{charNoSpaceCount}</span>
        </div>
        <div className="p-3 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] text-center">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">Sentences</span>
          <span className="text-xl font-bold text-blue-500">{sentenceCount}</span>
        </div>
        <div className="p-3 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] text-center">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">Paragraphs</span>
          <span className="text-xl font-bold text-blue-500">{paragraphCount}</span>
        </div>
        <div className="p-3 rounded-lg bg-[var(--color-background)] border border-[var(--color-border)] text-center">
          <span className="block text-xs text-[var(--color-text-secondary)] font-medium mb-1">Reading Time</span>
          <span className="text-xl font-bold text-blue-500">{readingTime} min</span>
        </div>
      </div>

      <div className="flex gap-3">
        <button onClick={() => setText('')} className="btn-secondary">
          <Trash2 className="w-4 h-4" /> Clear Text
        </button>
        <button onClick={handleCopy} className="btn-primary">
          {copied ? <Check className="w-4 h-4" /> : <Copy className="w-4 h-4" />}
          {copied ? 'Copied!' : 'Copy Text'}
        </button>
      </div>
    </div>
  );
}

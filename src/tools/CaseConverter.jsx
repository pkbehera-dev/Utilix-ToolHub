import React, { useState } from 'react';
import { Copy, Check } from 'lucide-react';

export default function CaseConverter() {
  const [text, setText] = useState('');
  const [copied, setCopied] = useState(false);

  const convert = (action) => {
    let str = text;
    if (action === 'upper') setText(str.toUpperCase());
    if (action === 'lower') setText(str.toLowerCase());
    if (action === 'title') setText(str.toLowerCase().replace(/(?:^|\s|-|_)\S/g, m => m.toUpperCase()));
    if (action === 'sentence') setText(str.toLowerCase().replace(/(^\s*|[.!?]\s+)([a-z])/g, (m, p1, p2) => p1 + p2.toUpperCase()));
    if (action === 'camel') setText(str.toLowerCase().replace(/[^a-zA-Z0-9]+(.)/g, (m, chr) => chr.toUpperCase()));
    if (action === 'pascal') {
      const camel = str.toLowerCase().replace(/[^a-zA-Z0-9]+(.)/g, (m, chr) => chr.toUpperCase());
      setText(camel.charAt(0).toUpperCase() + camel.slice(1));
    }
    if (action === 'snake') setText(str.trim().toLowerCase().replace(/[\s\W_]+/g, '_'));
    if (action === 'kebab') setText(str.trim().toLowerCase().replace(/[\s\W_]+/g, '-'));
  };

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
        placeholder="Type or paste text to convert..."
        rows={6}
        className="form-textarea mb-6"
      />

      <div className="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mb-6">
        <button onClick={() => convert('upper')} className="btn-secondary justify-center text-xs">UPPERCASE</button>
        <button onClick={() => convert('lower')} className="btn-secondary justify-center text-xs">lowercase</button>
        <button onClick={() => convert('title')} className="btn-secondary justify-center text-xs">Title Case</button>
        <button onClick={() => convert('sentence')} className="btn-secondary justify-center text-xs">Sentence case</button>
        <button onClick={() => convert('camel')} className="btn-secondary justify-center text-xs">camelCase</button>
        <button onClick={() => convert('pascal')} className="btn-secondary justify-center text-xs">PascalCase</button>
        <button onClick={() => convert('snake')} className="btn-secondary justify-center text-xs">snake_case</button>
        <button onClick={() => convert('kebab')} className="btn-secondary justify-center text-xs">kebab-case</button>
      </div>

      <button onClick={handleCopy} className="btn-primary">
        {copied ? <Check className="w-4 h-4" /> : <Copy className="w-4 h-4" />}
        {copied ? 'Copied!' : 'Copy Result'}
      </button>
    </div>
  );
}

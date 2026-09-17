import React, { useState, useEffect } from 'react';
import { Copy, Check, RotateCw } from 'lucide-react';

const LOREM_WORDS = [
  "lorem", "ipsum", "dolor", "sit", "amet", "consectetur", "adipiscing", "elit", "sed", "do",
  "eiusmod", "tempor", "incididunt", "ut", "labore", "et", "dolore", "magna", "aliqua", "ut",
  "enim", "ad", "minim", "veniam", "quis", "nostrud", "exercitation", "ullamco", "laboris", "nisi",
  "ut", "aliquip", "ex", "ea", "commodo", "consequat", "duis", "aute", "irure", "dolor", "in",
  "reprehenderit", "in", "voluptate", "velit", "esse", "cillum", "dolore", "eu", "fugiat", "nulla",
  "pariatur", "excepteur", "sint", "occaecat", "cupidatat", "non", "proident", "sunt", "in", "culpa",
  "qui", "officia", "deserunt", "mollit", "anim", "id", "est", "laborum"
];

export default function LoremIpsum() {
  const [count, setCount] = useState(3);
  const [type, setType] = useState('paragraphs');
  const [output, setOutput] = useState('');
  const [copied, setCopied] = useState(false);

  const getRandomSentence = (min = 8, max = 15) => {
    const len = Math.floor(Math.random() * (max - min + 1)) + min;
    const words = [];
    for (let i = 0; i < len; i++) {
      words.push(LOREM_WORDS[Math.floor(Math.random() * LOREM_WORDS.length)]);
    }
    const str = words.join(' ');
    return str.charAt(0).toUpperCase() + str.slice(1) + '.';
  };

  const generate = () => {
    if (type === 'words') {
      const res = [];
      for (let i = 0; i < count; i++) res.push(LOREM_WORDS[i % LOREM_WORDS.length]);
      setOutput(res.join(' '));
    } else if (type === 'sentences') {
      const res = [];
      for (let i = 0; i < count; i++) res.push(getRandomSentence());
      setOutput(res.join(' '));
    } else {
      const paragraphs = [];
      for (let p = 0; p < count; p++) {
        const sentenceCount = Math.floor(Math.random() * 3) + 4;
        const paragraph = [];
        for (let s = 0; s < sentenceCount; s++) paragraph.push(getRandomSentence());
        paragraphs.push(paragraph.join(' '));
      }
      setOutput(paragraphs.join('\n\n'));
    }
  };

  useEffect(() => { generate(); }, [count, type]);

  const handleCopy = () => {
    if (!output) return;
    navigator.clipboard.writeText(output);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div className="glass-card p-6">
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Quantity</label>
          <input
            type="number"
            min="1"
            max="50"
            value={count}
            onChange={(e) => setCount(Math.max(1, parseInt(e.target.value) || 1))}
            className="form-input"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-[var(--color-text-secondary)] mb-1">Type</label>
          <select value={type} onChange={(e) => setType(e.target.value)} className="form-select">
            <option value="paragraphs">Paragraphs</option>
            <option value="sentences">Sentences</option>
            <option value="words">Words</option>
          </select>
        </div>
      </div>

      <button onClick={generate} className="btn-primary mb-6">
        <RotateCw className="w-4 h-4" /> Generate Text
      </button>

      <textarea
        value={output}
        readOnly
        rows={8}
        className="form-textarea readonly-input mb-4"
      />

      <button onClick={handleCopy} className="btn-primary">
        {copied ? <Check className="w-4 h-4" /> : <Copy className="w-4 h-4" />}
        {copied ? 'Copied!' : 'Copy Text'}
      </button>
    </div>
  );
}

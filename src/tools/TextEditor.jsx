import React, { useState } from 'react';
import { Copy, Download, Check, Bold, Italic, Code, Heading1, Heading2 } from 'lucide-react';

export default function TextEditor() {
  const [text, setText] = useState('');
  const [copied, setCopied] = useState(false);

  const wrapText = (prefix, suffix = '') => {
    setText(prev => prev + prefix + suffix);
  };

  const handleCopy = () => {
    if (!text) return;
    navigator.clipboard.writeText(text);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  const handleDownload = () => {
    const element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    element.setAttribute('download', 'document.txt');
    element.style.display = 'none';
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);
  };

  return (
    <div className="glass-card p-6">
      <div className="flex gap-2 mb-3">
        <button onClick={() => wrapText('**', '**')} className="btn-secondary py-1 px-2.5 text-xs"><Bold className="w-3.5 h-3.5" /></button>
        <button onClick={() => wrapText('*', '*')} className="btn-secondary py-1 px-2.5 text-xs"><Italic className="w-3.5 h-3.5" /></button>
        <button onClick={() => wrapText('# ')} className="btn-secondary py-1 px-2.5 text-xs"><Heading1 className="w-3.5 h-3.5" /></button>
        <button onClick={() => wrapText('## ')} className="btn-secondary py-1 px-2.5 text-xs"><Heading2 className="w-3.5 h-3.5" /></button>
        <button onClick={() => wrapText('`', '`')} className="btn-secondary py-1 px-2.5 text-xs"><Code className="w-3.5 h-3.5" /></button>
      </div>

      <textarea
        value={text}
        onChange={(e) => setText(e.target.value)}
        placeholder="Type or write Markdown notes..."
        rows={10}
        className="form-textarea mb-4 font-mono text-sm"
      />

      <div className="flex gap-3">
        <button onClick={handleCopy} className="btn-primary">
          {copied ? <Check className="w-4 h-4" /> : <Copy className="w-4 h-4" />}
          {copied ? 'Copied!' : 'Copy Document'}
        </button>
        <button onClick={handleDownload} className="btn-secondary">
          <Download className="w-4 h-4" /> Download .txt
        </button>
      </div>
    </div>
  );
}

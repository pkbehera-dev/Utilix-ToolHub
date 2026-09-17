import React from 'react';
import { TOOLS } from '../data/toolsData';
import { ArrowLeft, Layers, ShieldCheck } from 'lucide-react';

import WordCounter from '../tools/WordCounter';
import CaseConverter from '../tools/CaseConverter';
import Base64Tool from '../tools/Base64Tool';
import RemoveDuplicates from '../tools/RemoveDuplicates';
import LoremIpsum from '../tools/LoremIpsum';
import TextDiff from '../tools/TextDiff';
import TextEditor from '../tools/TextEditor';
import AsciiArt from '../tools/AsciiArt';
import AgeCalculator from '../tools/AgeCalculator';
import BmiCalculator from '../tools/BmiCalculator';
import GstCalculator from '../tools/GstCalculator';
import TimeCalculator from '../tools/TimeCalculator';
import UnitConverter from '../tools/UnitConverter';
import HashGenerator from '../tools/HashGenerator';
import PasswordGenerator from '../tools/PasswordGenerator';
import PasswordHasher from '../tools/PasswordHasher';
import RandomNumberGen from '../tools/RandomNumberGen';
import QrCodeGenerator from '../tools/QrCodeGenerator';
import JsonFormatter from '../tools/JsonFormatter';
import PomodoroTimer from '../tools/PomodoroTimer';
import Stopwatch from '../tools/Stopwatch';
import Timer from '../tools/Timer';
import CoinFlip from '../tools/CoinFlip';
import DiceRoller from '../tools/DiceRoller';

const TOOL_COMPONENTS = {
  'word-counter': WordCounter,
  'case-converter': CaseConverter,
  'base64-encoder-decoder': Base64Tool,
  'remove-duplicate-lines': RemoveDuplicates,
  'lorem-ipsum-generator': LoremIpsum,
  'text-diff-checker': TextDiff,
  'text-editor': TextEditor,
  'text-to-ascii-art': AsciiArt,
  'age-calculator': AgeCalculator,
  'bmi-calculator': BmiCalculator,
  'gst-calculator': GstCalculator,
  'time-calculator': TimeCalculator,
  'unit-converter': UnitConverter,
  'hash-generator': HashGenerator,
  'password-generator': PasswordGenerator,
  'password-hasher': PasswordHasher,
  'random-number-generator': RandomNumberGen,
  'qr-code-generator': QrCodeGenerator,
  'json-formatter': JsonFormatter,
  'pomodoro-timer': PomodoroTimer,
  'stopwatch': Stopwatch,
  'timer': Timer,
  'coin-flip': CoinFlip,
  'dice-roller': DiceRoller
};

export default function ToolView({ slug }) {
  const tool = TOOLS.find(t => t.slug === slug);
  const ToolComponent = TOOL_COMPONENTS[slug];

  if (!tool || !ToolComponent) {
    return (
      <div className="text-center py-16 space-y-4">
        <h2 className="text-2xl font-bold text-rose-500">Tool Not Found</h2>
        <p className="text-sm text-[var(--color-text-secondary)]">The tool you are looking for does not exist.</p>
        <a href="#/" className="btn-primary inline-flex">
          <ArrowLeft className="w-4 h-4" /> Back to Utilities
        </a>
      </div>
    );
  }

  return (
    <div className="space-y-6 py-6 animate-fade-in max-w-4xl mx-auto">
      {/* Breadcrumb / Back button */}
      <a href="#/" className="inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--color-text-secondary)] hover:text-blue-500 transition-colors">
        <ArrowLeft className="w-3.5 h-3.5" /> Back to All Utilities
      </a>

      {/* Tool Header */}
      <div className="space-y-2 border-b border-[var(--color-border)] pb-6">
        <div className="flex items-center gap-3">
          <div className="p-3 rounded-xl bg-blue-500/10 text-blue-500">
            <Layers className="w-6 h-6" />
          </div>
          <div>
            <h1 className="text-2xl sm:text-3xl font-extrabold text-[var(--color-text-primary)]">{tool.name}</h1>
            <p className="text-sm text-[var(--color-text-secondary)]">{tool.description}</p>
          </div>
        </div>
      </div>

      {/* Tool Main Component */}
      <ToolComponent />

      {/* Privacy Notice Banner */}
      <div className="p-4 rounded-xl bg-blue-500/5 border border-blue-500/10 flex items-start gap-3 text-xs text-[var(--color-text-secondary)]">
        <ShieldCheck className="w-4 h-4 text-blue-500 shrink-0 mt-0.5" />
        <span>
          All calculations, text processing, and cryptographic hashing take place directly inside your web browser. No data is sent to external servers.
        </span>
      </div>
    </div>
  );
}

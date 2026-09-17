export const CATEGORIES = [
  { name: "Text & Utilities", slug: "text", icon: "FileText" },
  { name: "Calculators", slug: "calc", icon: "Calculator" },
  { name: "Generators & Security", slug: "gen", icon: "Shield" },
  { name: "Utilities & Fun", slug: "util", icon: "Clock" }
];

export const TOOLS = [
  {
    slug: "word-counter",
    name: "Word & Character Counter",
    category: "text",
    icon: "Calculator",
    description: "Count words, characters, sentences, paragraphs, and estimated reading time in real-time.",
    tags: ["text", "counter", "words", "characters", "seo"]
  },
  {
    slug: "case-converter",
    name: "Case Converter",
    category: "text",
    icon: "Type",
    description: "Convert text between UPPERCASE, lowercase, Title Case, camelCase, snake_case, PascalCase, and kebab-case.",
    tags: ["text", "case", "uppercase", "camelcase", "developer"]
  },
  {
    slug: "base64-encoder-decoder",
    name: "Base64 Encoder / Decoder",
    category: "text",
    icon: "Code",
    description: "Encode text or data to Base64 format or decode Base64 strings back to plain text instantly.",
    tags: ["base64", "encode", "decode", "developer", "crypto"]
  },
  {
    slug: "remove-duplicate-lines",
    name: "Remove Duplicate Lines",
    category: "text",
    icon: "Filter",
    description: "Deduplicate text line by line with options for case-sensitivity, trimming, and empty line removal.",
    tags: ["text", "lines", "duplicate", "cleaner"]
  },
  {
    slug: "lorem-ipsum-generator",
    name: "Lorem Ipsum Generator",
    category: "text",
    icon: "AlignLeft",
    description: "Generate customizable placeholder text by paragraphs, sentences, or word counts.",
    tags: ["lorem", "ipsum", "text", "dummy", "design"]
  },
  {
    slug: "text-diff-checker",
    name: "Text Diff Checker",
    category: "text",
    icon: "FileCode",
    description: "Compare two text snippets side-by-side to highlight added, removed, or modified lines.",
    tags: ["diff", "compare", "code", "text", "git"]
  },
  {
    slug: "text-editor",
    name: "Rich Text & Markdown Editor",
    category: "text",
    icon: "Edit3",
    description: "Write, format, preview Markdown or HTML, track statistics, and download text documents.",
    tags: ["editor", "markdown", "text", "writer"]
  },
  {
    slug: "text-to-ascii-art",
    name: "Text to ASCII Art",
    category: "text",
    icon: "Terminal",
    description: "Convert text into styled ASCII art banner fonts for code comments, banners, and README files.",
    tags: ["ascii", "banner", "art", "font"]
  },
  {
    slug: "age-calculator",
    name: "Age Calculator",
    category: "calc",
    icon: "Calendar",
    description: "Calculate exact age in years, months, weeks, days, hours, and minutes with upcoming birthday countdown.",
    tags: ["age", "date", "calculator", "birthday"]
  },
  {
    slug: "bmi-calculator",
    name: "BMI Calculator",
    category: "calc",
    icon: "Activity",
    description: "Calculate Body Mass Index (BMI) with metric and imperial units, health status, and ideal weight range.",
    tags: ["bmi", "health", "weight", "fitness"]
  },
  {
    slug: "gst-calculator",
    name: "GST / VAT Calculator",
    category: "calc",
    icon: "Percent",
    description: "Calculate gross price, net price, and tax amounts for GST or VAT (Inclusive / Exclusive).",
    tags: ["gst", "vat", "tax", "finance", "money"]
  },
  {
    slug: "time-calculator",
    name: "Time Duration Calculator",
    category: "calc",
    icon: "Clock",
    description: "Add, subtract, and compute total duration differences between dates and times.",
    tags: ["time", "duration", "hours", "calculator"]
  },
  {
    slug: "unit-converter",
    name: "Universal Unit Converter",
    category: "calc",
    icon: "Maximize2",
    description: "Convert units across Length, Weight, Temperature, Area, Volume, Speed, and Digital Storage.",
    tags: ["unit", "converter", "length", "weight", "temp"]
  },
  {
    slug: "hash-generator",
    name: "Hash Generator",
    category: "gen",
    icon: "Shield",
    description: "Generate real-time cryptographic hash digests (MD5, SHA-1, SHA-256, SHA-512) using Web Crypto API.",
    tags: ["hash", "sha256", "md5", "security", "crypto"]
  },
  {
    slug: "password-generator",
    name: "Password Generator",
    category: "gen",
    icon: "Key",
    description: "Generate strong, randomized passwords with custom character sets, length, and strength entropy evaluation.",
    tags: ["password", "generator", "security", "random"]
  },
  {
    slug: "password-hasher",
    name: "Client-Side Password Hasher",
    category: "gen",
    icon: "Lock",
    description: "Generate client-side cryptographic password digests (SHA-256, SHA-512, SHA-384, SHA-1, MD5, PBKDF2) securely in browser.",
    tags: ["password", "hash", "pbkdf2", "crypto", "sha512"]
  },
  {
    slug: "random-number-generator",
    name: "Random Number Generator",
    category: "gen",
    icon: "Shuffle",
    description: "Generate single or lists of random numbers within custom ranges with sorting and uniqueness options.",
    tags: ["random", "number", "rng", "generator"]
  },
  {
    slug: "qr-code-generator",
    name: "QR Code Generator",
    category: "gen",
    icon: "QrCode",
    description: "Create customizable QR codes for URLs, text, Wi-Fi, or contact info with instant PNG/SVG download.",
    tags: ["qr", "code", "generator", "url", "wifi"]
  },
  {
    slug: "json-formatter",
    name: "JSON Formatter & Validator",
    category: "gen",
    icon: "Braces",
    description: "Beautify, minify, validate, repair, and inspect JSON structures with error highlighting.",
    tags: ["json", "format", "beautify", "minify", "developer"]
  },
  {
    slug: "pomodoro-timer",
    name: "Pomodoro Focus Timer",
    category: "util",
    icon: "Timer",
    description: "Boost productivity with customizable Pomodoro work and break session intervals and audio alerts.",
    tags: ["pomodoro", "timer", "focus", "productivity"]
  },
  {
    slug: "stopwatch",
    name: "Precision Stopwatch",
    category: "util",
    icon: "Watch",
    description: "Track elapsed time with millisecond precision, lap split logging, and CSV export.",
    tags: ["stopwatch", "timer", "lap", "sports"]
  },
  {
    slug: "timer",
    name: "Countdown Timer",
    category: "util",
    icon: "Hourglass",
    description: "Set custom countdown timers with visual progress ring, pause/resume, and alarm sounds.",
    tags: ["timer", "countdown", "alarm"]
  },
  {
    slug: "coin-flip",
    name: "Coin Flip Simulator",
    category: "util",
    icon: "Coins",
    description: "Flip a 3D animated virtual coin for quick decision making with streak and result tallying.",
    tags: ["coin", "flip", "heads", "tails", "game"]
  },
  {
    slug: "dice-roller",
    name: "3D Dice Roller",
    category: "util",
    icon: "Dices",
    description: "Roll single or multiple polyhedral dice (d4, d6, d8, d10, d12, d20) with total score calculation.",
    tags: ["dice", "roll", "d6", "d20", "game"]
  }
];

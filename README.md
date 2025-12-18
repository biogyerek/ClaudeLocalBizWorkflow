# Claude Local Business Workflow

Generate SEO-optimized Hungarian local business websites through Claude Code conversation.

## Quick Start

```bash
# Clone and setup
git clone https://github.com/YOUR-USERNAME/claudelocalbizworkflow.git
cd claudelocalbizworkflow
cp .env.example .env

# Add your API key to .env, then:
claude
```

## What This Creates

- **60+ pages** with unique, SEO-optimized content
- **Clean URLs** (folder-based, no `.html` extensions)
- **GitHub Pages ready** (free hosting)
- **Hungarian language** prompts and content

## Features

- **No CMS required** - pure static HTML generation
- **LLM-powered** - OpenAI or Gemini for content
- **Rate-limited** - built-in API throttling
- **Progress tracking** - see generation status in real-time
- **Self-contained** - all prompts included

## Documentation

- [Production Guide](WEBSITE_PRODUCTION_GUIDE.md) - Step-by-step usage
- [Architecture](docs/architecture.md) - Technical details
- [PRD](docs/prd.md) - Requirements and user stories
- [Epics & Stories](docs/epics-stories.md) - Implementation breakdown

## Directory Structure

```
├── .env.example          # Environment template
├── locations.csv         # Service areas
├── prompts/              # Content generation prompts
│   ├── master-prompts.md
│   ├── home/
│   ├── service/
│   ├── location/
│   └── ...
├── templates/            # HTML templates (to be created)
├── output/               # Generated static site
└── docs/                 # Documentation
```

## Requirements

- Claude Code CLI
- OpenAI API key OR Google Gemini API key
- GitHub account (for hosting)

## License

MIT

#!/usr/bin/env sh

xvfb-run --auto-servernum --server-args="-screen 0 1280x960x24" -- npm run-script test:e2e

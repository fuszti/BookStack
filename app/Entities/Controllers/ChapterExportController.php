<?php

namespace BookStack\Entities\Controllers;

use BookStack\Entities\Queries\ChapterQueries;
use BookStack\Entities\Tools\ExportFormatter;
use BookStack\Exceptions\NotFoundException;
use BookStack\Http\Controller;
use Throwable;

class ChapterExportController extends Controller
{
    public function __construct(
        protected ChapterQueries $queries,
        protected ExportFormatter $exportFormatter,
    ) {
        $this->middleware('can:content-export');
    }

    /**
     * Exports a chapter to pdf.
     *
     * @throws NotFoundException
     * @throws Throwable
     */
    public function pdf(string $bookSlug, string $chapterSlug)
    {
        $chapter = $this->queries->findVisibleBySlugs($bookSlug, $chapterSlug);
        $pdfContent = $this->exportFormatter->chapterToPdf($chapter);

        return $this->download()->directly($pdfContent, $chapterSlug . '.pdf');
    }

    /**
     * Export a chapter to a self-contained HTML file.
     *
     * @throws NotFoundException
     * @throws Throwable
     */
    public function html(string $bookSlug, string $chapterSlug)
    {
        $chapter = $this->queries->findVisibleBySlugs($bookSlug, $chapterSlug);
        $containedHtml = $this->exportFormatter->chapterToContainedHtml($chapter);

        return $this->download()->directly($containedHtml, $chapterSlug . '.html');
    }

    /**
     * Export a chapter to a simple plaintext .txt file.
     *
     * @throws NotFoundException
     */
    public function plainText(string $bookSlug, string $chapterSlug)
    {
        $chapter = $this->queries->findVisibleBySlugs($bookSlug, $chapterSlug);
        $chapterText = $this->exportFormatter->chapterToPlainText($chapter);

        return $this->download()->directly($chapterText, $chapterSlug . '.txt');
    }

    /**
     * Export a chapter to a simple markdown file.
     *
     * @throws NotFoundException
     */
    public function markdown(string $bookSlug, string $chapterSlug)
    {
        $chapter = $this->queries->findVisibleBySlugs($bookSlug, $chapterSlug);
        $chapterText = $this->exportFormatter->chapterToMarkdown($chapter);

        return $this->download()->directly($chapterText, $chapterSlug . '.md');
    }
}

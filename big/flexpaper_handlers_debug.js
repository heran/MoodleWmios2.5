

/**
 * Handles the event of external links getting clicked in the document. 
 *
 * @example onExternalLinkClicked("http://www.google.com")
 *
 * @param String link
 */
function onExternalLinkClicked(link){
	jQuery("#txt_eventlog").val('onExternalLinkClicked:' + link + '\n' + jQuery("#txt_eventlog").val());	
}

/**
 * Recieves progress information about the document being loaded
 *
 * @example onProgress( 100,10000 );
 *
 * @param int loaded
 * @param int total
 */
function onProgress(loadedBytes,totalBytes){
	jQuery("#txt_progress").val('onProgress:' + loadedBytes + '/' + totalBytes + '\n');	
}

/**
 * Handles the event of a document is in progress of loading
 *
 */
function onDocumentLoading(){
	jQuery("#txt_eventlog").val('onDocumentLoading' + '\n' + jQuery("#txt_eventlog").val());	
}

/**
 * Handles the event of a document is in progress of loading
 *
 */
function onPageLoading(pageNumber){
	jQuery("#txt_eventlog").val('onPageLoading:' + pageNumber + '\n' + jQuery("#txt_eventlog").val());	
}

/**
 * Receives messages about the current page being changed
 *
 * @example onCurrentPageChanged( 10 );
 *
 * @param int pagenum
 */
function onCurrentPageChanged(pagenum){
	jQuery("#txt_eventlog").val('onCurrentPageChanged:' + pagenum + '\n' + jQuery("#txt_eventlog").val());
}

/**
 * Receives messages about the document being loaded
 *
 * @example onDocumentLoaded( 20 );
 *
 * @param int totalPages
 */
function onDocumentLoaded(totalPages){
	jQuery("#txt_eventlog").val('onDocumentLoaded:' + totalPages + '\n' + jQuery("#txt_eventlog").val());
}

/**
 * Receives messages about the page loaded
 *
 * @example onPageLoaded( 1 );
 *
 * @param int pageNumber
 */
function onPageLoaded(pageNumber){
	jQuery("#txt_eventlog").val('onPageLoaded:' + pageNumber + '\n' + jQuery("#txt_eventlog").val());
}

/**
 * Receives messages about the page loaded
 *
 * @example onErrorLoadingPage( 1 );
 *
 * @param int pageNumber
 */
function onErrorLoadingPage(pageNumber){
	jQuery("#txt_eventlog").val('onErrorLoadingPage:' + pageNumber + '\n' + jQuery("#txt_eventlog").val());
} 

/**
 * Receives error messages when a document is not loading properly
 *
 * @example onDocumentLoadedError( "Network error" );
 *
 * @param String errorMessage
 */
function onDocumentLoadedError(errMessage){
	jQuery("#txt_eventlog").val('onDocumentLoadedError:' + errMessage + '\n' + jQuery("#txt_eventlog").val());
}

/**
 * Receives error messages when a document has finished printed
 *
 * @example onDocumentPrinted();
 *
 */
function onDocumentPrinted(){
	jQuery("#txt_eventlog").val('onDocumentPrinted\n' + jQuery("#txt_eventlog").val());
}
function getRecordLink(recordId, label) {
  return '<a target="_blank"'
    + ' href="' + getRecordUrl(recordId) + '"'
    + ' title="record id: ' + recordId + '"'
    + ' class="external">' + label + '</a>';
}

function getRecordUrl(recordId) {
  return 'https://www.europeana.eu/api/v2/record' + recordId + '.json?wskey=hgQQMdjcG';
}

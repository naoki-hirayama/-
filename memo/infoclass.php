SELECT
  COUNT(*) AS CNT
FROM
  TEST_TBL
WHERE
  COL1 = 'AAA'
AND
  COL2 = 'BBB'
GROUP BY
  COL1
  
  "SELECT post_id, COUNT(*) AS cnt FROM replies WHERE post_id IN (".implode(',', $sanitized_ids).") GROUP BY post_id";
  "SELECT post_id, COUNT(*) AS cnt FROM (SELECT post_id FROM replies WHERE post_id IN (".implode(',', $sanitized_ids).") GROUP BY post_id)";
  
  SELECT
  COUNT(*) AS CNT
FROM (
	SELECT
	  COL1
	FROM
	  TEST_TBL
	WHERE
	  COL1 = 'AAA'
	AND
	  COL2 = 'BBB'
	GROUP BY
	  COL1
) A
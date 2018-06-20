<?
namespace app\helpers;

use Yii;
class OtaxonomyHelper {
	/*
	createTaxonmony($taxonomyData)
	getTaxonmony($taxonomyId)
	updateTaxonmony($taxonomyId, $taxonomyData)
	deleteTaxonmony($taxonomyId)
	
	createTerm(taxonomyId, termData)
	getTerm(termId)
	getTerms(taxonomyId, itemType, itemId)
	getTermIds(taxonomyId, itemType, itemId)
	updateTerm(termId, termData)
	updateTerms(taxonomyId, itemType, itemId, $terms, deleteOld)
	deleteTerm(termId)
	mergeTerms(termId, termId2)
	*/
	
	private $taxo = [];

	public function x__construct()
	{
		global $db;
		$q = $db->query('SELECT * FROM at_taxonomies ORDER BY name');
		if ($q->countReturnedRows() > 0) {
			foreach ($q->fetchAllRows() as $t) {
				$this->taxo[$t['alias']] = $t;
			}
		}
	}
	
	// TAXONOMY FUNCTIONS
	
	public function getTaxonomyId($taxonomyAlias)
	{
		return isset($this->taxo[$taxonomyAlias]) ? $this->taxo[$taxonomyAlias]['id'] : 0;
	}
	
	public function createTaxonomy($taxonomyData)
	{
		global $db;
		
		if (!is_array($taxonomyData)) $taxonomyData = array('name'=>$taxonomyData);
		if (!isset($taxonomyData['name']) || $taxonomyData['name'] == '') return false;
		
		$q = $db->query('INSERT INTO at_taxonomies (co, cb, uo, ub, status, name, alias, info, term_count, is_hierachical, is_multiple)
			VALUES (%s, %i, %s, %i, %s, %s, %s, %s, %i, %s, %s)',
			NOW,
			defined(MY_ID) ? MY_ID : 0,
			NOW,
			defined(MY_ID) ? MY_ID : 0,
			isset($taxonomyData['status']) ? $taxonomyData['status'] : 'on',
			isset($taxonomyData['name']) ? $taxonomyData['name'] : '',
			isset($taxonomyData['alias']) ? $taxonomyData['alias'] : \fURL::makeFriendly($taxonomyData['name'], '-'),
			isset($taxonomyData['info']) ? $taxonomyData['info'] : '',
			isset($taxonomyData['term_count']) ? $taxonomyData['term_count'] : 0,
			isset($taxonomyData['is_hierachical']) ? $taxonomyData['is_hierachical'] : 'yes',
			isset($taxonomyData['is_multiple']) ? $taxonomyData['is_multiple'] : 'yes'
			);
		return $q->getAutoIncrementedValue();
	}
	
	public function getTaxonomy($taxonomyId)
	{
		global $db;
		$q = $db->query('SELECT * FROM at_taxonomies WHERE id=%i LIMIT 1', $taxonomyId);
		return $q->countReturnedRows() > 0 ? $q->fetchRow() : null;
	}
	
	public function updateTaxonomy($taxonomyId, $taxonomyData)
	{
		global $db;
		
		if (!is_array($taxonomyData)) $taxonomyData = array('name'=>$taxonomyData);
		if (!isset($taxonomyData['name']) || $taxonomyData['name'] == '') return false;
		
		$q = $db->query('UPDATE at_taxonomies SET uo=%s, ub=%i, status=%s, name=%s, alias=%s, info=%s, term_count=%i, is_hierachical=%s, is_multiple=%s WHERE id=%i LIMIT 1',
			NOW,
			defined(MY_ID) ? MY_ID : 0,
			isset($taxonomyData['status']) ? $taxonomyData['status'] : 'on',
			isset($taxonomyData['name']) ? $taxonomyData['name'] : '',
			isset($taxonomyData['alias']) ? $taxonomyData['alias'] : fURL::makeFriendly($taxonomyData['name'], '-'),
			isset($taxonomyData['info']) ? $taxonomyData['info'] : '',
			isset($taxonomyData['term_count']) ? $taxonomyData['term_count'] : 0,
			isset($taxonomyData['is_hierachical']) ? $taxonomyData['is_hierachical'] : 'yes',
			isset($taxonomyData['is_multiple']) ? $taxonomyData['is_multiple'] : 'yes',
			$taxonomyId
			);
		return $q->getAffectedRows() > 0;
	}
	
	public function deleteTaxonomy($taxonomyId)
	{
		global $db;
		$q = $db->query('DELETE FROM at_taxonomies WHERE id=%i LIMIT 1', $taxonomyId);
		return $q->getAffectedRows() > 0;
	}
	
	// TERM FUNCTIONS
	public static function createTerm($taxonomyId, $termData)
	{		
		if (!is_array($termData)) {
			$termData = ['name'=>$termData];
		}
		if (!isset($termData['name']) || $termData['name'] == '') {
			return false;
		}

		$sql = 'INSERT INTO at_terms (created_at, created_by, updated_at, updated_by, status, taxonomy_id, pid, sorder, slevel, rcount, name, alias, image, info)
			VALUES (:co, :cb, :uo, :ub, :status, :taxonomy_id, :pid, :sorder, :slevel, :rcount, :name, :alias, :image, :info)';
		Yii::$app->db->createCommand($sql, [
			':co'=>NOW,
			':cb'=>defined(MY_ID) ? MY_ID : 0,
			':uo'=>NOW,
			':ub'=>defined(MY_ID) ? MY_ID : 0,
			':status'=>isset($termData['status']) ? $termData['status'] : 'on',
			':taxonomy_id'=>$taxonomyId,
			':pid'=>isset($termData['pid']) ? $termData['pid'] : 0,
			':sorder'=>isset($termData['sorder']) ? $termData['sorder'] : 0,
			':slevel'=>isset($termData['slevel']) ? $termData['slevel'] : 0,
			':rcount'=>0,
			':name'=>isset($termData['name']) ? $termData['name'] : '',
			':alias'=>isset($termData['alias']) ? $termData['alias'] : \fURL::makeFriendly($termData['name'], '-'),
			':image'=>isset($termData['image']) ? $termData['image'] : '',
			':info'=>isset($termData['info']) ? $termData['info'] : ''
			])->execute();
		return Yii::$app->db->getLastInsertID();
	}
	
	public function getTerm($termId)
	{
		global $db;
		$q = $db->query('SELECT * FROM at_terms WHERE id=%i LIMIT 1', $termId);
		return $q->countReturnedRows() > 0 ? $q->fetchRow() : null;
	}
	
	public function updateTerm($termId, $termData)
	{
		global $db;
		
		if (!is_array($termData)) $termData = array('name'=>$termData);
		if (!isset($termData['name']) || $termData['name'] == '') return false;
		
    $q = $db->query('UPDATE at_terms SET uo=%s, ub=%i, status=%s, pid=%i, sorder=%i, slevel=%i, rcount=%i, name=%s, alias=%s, image=%s, info=%s WHERE id=%i LIMIT 1',
      NOW,
			defined(MY_ID) ? MY_ID : 0,
			isset($termData['status']) ? $termData['status'] : 'on',
			isset($termData['pid']) ? $termData['pid'] : 0,
			isset($termData['sorder']) ? $termData['sorder'] : 0,
			isset($termData['slevel']) ? $termData['slevel'] : 0,
			isset($termData['rount']) ? $termData['rcount'] : 0,
			isset($termData['name']) ? $termData['name'] : '',
			isset($termData['alias']) ? $termData['alias'] : fURL::makeFriendly($termData['name'], '-'),
			isset($termData['image']) ? $termData['image'] : '',
			isset($termData['info']) ? $termData['info'] : '',
			$termId
		);
		return $q->countAffectedRows() > 0;
	}
	
	public function deleteTerm($termId)
	{
		global $db;
		$q = $db->query('DELETE FROM at_term_rel WHERE term_id=%i', $termId);
		$q = $db->query('DELETE FROM at_terms WHERE id=%i LIMIT 1', $termId);
		return $q->getAffectedRows() > 0;
	}

	// TERMS FUNCTIONS
	public function getTerms($taxonomyId = null, $rType = null, $rId = null)
	{
		global $db;
		// Get taxonomy terms
		
		if (isset($rId) && isset($rType) && isset($taxonomyId)) {
			$q = $db->query('SELECT t.* FROM at_terms t, at_term_rel tr WHERE tr.term_id=t.id AND tr.rid=%i AND tr.rtype=%s AND t.taxonomy_id=%i ORDER BY name LIMIT 100', $rId, $rType, $taxonomyId);
			return $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		}
		
		if (!isset($rId) && isset($rType) && isset($taxonomyId)) {
			$q = $db->query('SELECT t.* FROM at_terms t, at_term_rel tr WHERE tr.term_id=t.id AND tr.rtype=%s AND t.taxonomy_id=%i ORDER BY name LIMIT 100', $rType, $taxonomyId);
			return $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		}
		
		if (!isset($rId) && !isset($rType) && isset($taxonomyId)) {
			$q = $db->query('SELECT t.* FROM at_terms t WHERE t.taxonomy_id=%i ORDER BY name LIMIT 100', $taxonomyId);
			return $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();
		}
	}
	
	public static function getTermIds($taxonomyId = null, $rType = null, $rId = null)
	{
		global $db;

		$return = [];
		
		if (isset($rId) && isset($rType) && isset($taxonomyId)) {
			$sql = 'SELECT t.id FROM at_terms t, at_term_rel tr WHERE tr.term_id=t.id AND tr.rid=:rid AND tr.rtype=:rtype AND t.taxonomy_id=:taxo_id ORDER BY name LIMIT 100';
			$terms = Yii::$app->db->createCommand($sql, [':rid'=>$rId, ':rtype'=>$rType, ':taxo_id'=>$taxonomyId])->queryAll();
		}
		
		if (!isset($rId) && isset($rType) && isset($taxonomyId)) {
			$sql = 'SELECT t.id FROM at_terms t, at_term_rel tr WHERE tr.term_id=t.id AND tr.rtype=:rtype AND t.taxonomy_id=:taxo_id ORDER BY name LIMIT 100';
			$terms = Yii::$app->db->createCommand($sql, [':rtype'=>$rType, ':taxo_id'=>$taxonomyId])->queryAll();
		}
		
		if (!isset($rId) && !isset($rType) && isset($taxonomyId)) {
			$sql = 'SELECT t.id FROM at_terms t WHERE t.taxonomy_id=:taxo_id ORDER BY name LIMIT 100';
			$terms = Yii::$app->db->createCommand($sql, [':taxo_id'=>$taxonomyId])->queryAll();
		}
		
		if ($terms) {
			foreach ($terms as $term) {
				$return[] = $term['id'];
			}
		}
		return $return;
	}
	
	// Cap nhat cac Term moi cho item, Terms = string OR array(int), function se tu loc
	public static function updateTerms($taxonomyId, $rType, $rId, $termList, $deleteOld = true)
	{
		// Chuyen terms thanh termArray
		$termArray = [];
		if (is_array($termList)) {
			$termArray = $termList;
		} else {
			if ($termList != '') {
				$terms = \fUTF8::explode($termList, ',');
				foreach ($terms as $term) {
					$term = \fUTF8::trim($term);
					if ($term != '') {
						// Kiem tra xem co Term nay trong taxonomyId chua
						$sql = 'SELECT id FROM at_terms WHERE taxonomy_id=:taxo_id AND name=:name LIMIT 1';
						$termId = Yii::$app->db->createCommand($sql, [':taxo_id'=>$taxonomyId, ':name'=>$term])->queryScalar();
						if (!$termId) {
							// Chua co: them moi
							$termId = self::createTerm($taxonomyId, $term);
						}
						$termArray[] = $termId;
					}
				}
			}
		}
		$termArray = array_unique($termArray);

		// Hien tai
		$currentTerms = self::getTermIds($taxonomyId, $rType, $rId);
		// Xoa cu
		foreach ($currentTerms as $ct) {
			if (!in_array($ct, $termArray)) {
				if ($deleteOld) {
					$sql = 'DELETE FROM at_term_rel WHERE term_id=:term_id AND rtype=:rtype AND rid=:rid';
					Yii::$app->db->createCommand($sql, [':term_id'=>$ct, ':rtype'=>$rType, ':rid'=>$rId])->execute();

					$sql = 'UPDATE at_terms SET rcount=(SELECT COUNT(*) FROM at_term_rel WHERE term_id=:id) WHERE id=:id LIMIT 1';
					Yii::$app->db->createCommand($sql, [':id'=>$ct])->execute();
				}
			}
		}
		// Them moi
		foreach ($termArray as $t) {
			if (!in_array($t, $currentTerms)) {
				$newTerms[] = $t;
			}
		}
		if (empty($newTerms)) {
			return false;
		}
		foreach ($newTerms as $nt) {
			$sql = 'INSERT INTO at_term_rel (term_id, rtype, rid) VALUES (:term_id, :rtype, :rid)';
			Yii::$app->db->createCommand($sql, [':term_id'=>$nt, ':rtype'=>$rType, ':rid'=>$rId])->execute();
			$sql = 'UPDATE at_terms SET rcount=(SELECT COUNT(*) FROM at_term_rel WHERE term_id=:term_id) WHERE id=:id LIMIT 1';
			Yii::$app->db->createCommand($sql, [':term_id'=>$nt, ':id'=>$nt])->execute();
		}
		return true;
	}

} // end of Class hTaxonomyManager
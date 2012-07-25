<?php
//  ------------------------------------------------------------------------ //
//                      BOOKSHOP - MODULE FOR XOOPS 2                		 //
//                  Copyright (c) 2007, 2008 Instant Zero                    //
//                     <http://www.instant-zero.com/>                        //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH.'/class/xoopsobject.php';
if (!class_exists('Bookshop_XoopsPersistableObjectHandler')) {
	include_once XOOPS_ROOT_PATH.'/modules/bookshop/class/PersistableObjectHandler.php';
}

class bookshop_votedata extends Bookshop_Object
{
	function bookshop_votedata()
	{
		$this->initVar('vote_ratingid',XOBJ_DTYPE_INT,null,false);
		$this->initVar('vote_book_id',XOBJ_DTYPE_INT,null,false);
		$this->initVar('vote_uid',XOBJ_DTYPE_INT,null,false);
		$this->initVar('vote_rating',XOBJ_DTYPE_INT,null,false);
		$this->initVar('vote_ratinghostname',XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar('vote_ratingtimestamp',XOBJ_DTYPE_INT,null,false);
	}
}


class BookshopBookshop_votedataHandler extends Bookshop_XoopsPersistableObjectHandler
{
	function BookshopBookshop_votedataHandler($db)
	{	//													Table					Classe			 Id
		$this->BookXoopsPersistableObjectHandler($db, 'bookshop_votedata', 'bookshop_votedata', 'vote_ratingid');
	}


	/**
	 * Renvoie le nombre total de votes pour un livre ainsi que la sommes des votes
	 *
	 * @param integer $book_id Identifiant du livre
	 * @param integer $totalVotes Variable passée par référence et devant contenir le nombre total de votes du livre
	 * @param integer $sumRating Variable passée par référence et devant contenir le cumul des votes
	 * @return none Rien
	 */
	function getCountRecordSumRating($book_id, &$totalVotes, &$sumRating)
	{
		$sql = "SELECT count( * ) AS cpt, sum( vote_rating ) AS sum_rating FROM ".$this->table." WHERE vote_book_id = ".intval($book_id);
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        } else {
     		$myrow = $this->db->fetchArray($result);
			$totalVotes = $myrow['cpt'];
			$sumRating = $myrow['sum_rating'];
        }
	}

	/**
	 * Returns the (x) last votes
	 *
	 * @param integer $start Starting position
	 * @param integer $limit count of items to return
	 * @return array Array of votedata objects
	 */
	function getLastVotes($start=0, $limit=0)
	{
		$tbl_datas = array();
		$criteria = new Criteria('vote_ratingid', 0, '<>');
		$criteria->setLimit($limit);
		$criteria->setStart($start);
		$criteria->setSort('vote_ratingtimestamp');
		$criteria->setOrder('DESC');
		$tbl_datas = $this->getObjects($criteria, true);
		return $tbl_datas;
	}
}
?>
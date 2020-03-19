<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CodeMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->init();
    }

    /**
     * init();
     */
    public function init()
    {
        $arrayGroupCodesList = $this->initGroupCodesList();
	    $arrayCodesList = $this->initCodesList();

	    foreach ($arrayGroupCodesList as $element) :
		    $group_id = trim($element['group_id']);
		    $group_name = trim($element['group_name']);

		    DB::table('tbl_codes_master')->insert([
			    'group_id' => $group_id,
			    'group_name' => $group_name,
			    'created_at' => \Carbon\Carbon::now(),
			    'updated_at' => \Carbon\Carbon::now(),
		    ]);

		    foreach($arrayCodesList[$group_id] as $element_code):

		        $code_id = trim($element_code['code_id']);
		        $code_name = trim($element_code['code_name']);

		        $endCodeid = $group_id.$code_id;

			    DB::table('tbl_codes_master')->insert([
				    'group_id' => $group_id,
				    'group_name' => NULL,
				    'code_id' => $endCodeid,
                    'code_name' => $code_name,
                    'active' => 'Y',
				    'created_at' => \Carbon\Carbon::now(),
				    'updated_at' => \Carbon\Carbon::now(),
			    ]);

			endforeach;


		endforeach;

    }

    /**
	 * 그룹 코드 리스트
	 * @return array
	 */
	public function initGroupCodesList() : array
    {
	    return [
		    [ 'group_id' => 'A01', 'group_name' => '클라이언트 타입' ],
		    [ 'group_id' => 'A10', 'group_name' => '사용자 상태' ],
		    [ 'group_id' => 'A20', 'group_name' => '사용자 레벨' ],
            [ 'group_id' => 'A21', 'group_name' => '사용자 성별' ],
            [ 'group_id' => 'B11', 'group_name' => '북 카테고리(학년)' ],
            [ 'group_id' => 'C11', 'group_name' => '독서 활동 카테고리' ],
            [ 'group_id' => 'S01', 'group_name' => '시스템' ],
	    ];

    }


	/**
	 * 코드 리스트
	 * @return array
	 */
    public function initCodesList() : array
    {
		return [
			'A01' =>
				[
					[ 'code_id' => '001', 'code_name' => 'Web' ],
					[ 'code_id' => '002', 'code_name' => 'iOS' ],
					[ 'code_id' => '003', 'code_name' => 'Android' ],
				],
			'A10' =>
				[
					[ 'code_id' => '000', 'code_name' => '대기' ],
					[ 'code_id' => '010', 'code_name' => '이메일 인증 완료(정상)' ],
				],
			'A20' =>
				[
					[ 'code_id' => '000', 'code_name' => '일반 사용자' ],
					[ 'code_id' => '900', 'code_name' => '일반 관리자' ],
					[ 'code_id' => '999', 'code_name' => '최고 관리자' ],
				],
			'A21' =>
				[
					[ 'code_id' => '000', 'code_name' => '비공개' ],
					[ 'code_id' => '010', 'code_name' => '남성' ],
					[ 'code_id' => '020', 'code_name' => '여성' ],
                ],
            'S01' =>
				[
					[ 'code_id' => '000', 'code_name' => '비사용' ],
					[ 'code_id' => '001', 'code_name' => '사용' ],
                ],
            'B11' =>
				[
					[ 'code_id' => '000', 'code_name' => '없음' ],
                    [ 'code_id' => '110', 'code_name' => '초등학교 1학년' ],
                    [ 'code_id' => '120', 'code_name' => '초등학교 2학년' ],
                    [ 'code_id' => '130', 'code_name' => '초등학교 3학년' ],
                    [ 'code_id' => '140', 'code_name' => '초등학교 4학년' ],
                    [ 'code_id' => '150', 'code_name' => '초등학교 5학년' ],
                    [ 'code_id' => '160', 'code_name' => '초등학교 6학년' ],
                    [ 'code_id' => '210', 'code_name' => '중학교' ],
                    [ 'code_id' => '310', 'code_name' => '고등학교' ],
                    [ 'code_id' => '410', 'code_name' => '대학교' ],
                ],
            'C11' =>
				[
					[ 'code_id' => '000', 'code_name' => '느낀점 쓰기' ],
                    [ 'code_id' => '110', 'code_name' => '뒷이야기 꾸미기' ],
                    [ 'code_id' => '120', 'code_name' => '편지 쓰기' ],
                    [ 'code_id' => '130', 'code_name' => '상상 일기 쓰기' ],
				],
		];

	}
}

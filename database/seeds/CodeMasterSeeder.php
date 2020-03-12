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

		    DB::table('tbl_common_code_master')->insert([
			    'group_id' => $group_id,
			    'group_name' => $group_name,
			    'created_at' => \Carbon\Carbon::now(),
			    'updated_at' => \Carbon\Carbon::now(),
		    ]);

		    foreach($arrayCodesList[$group_id] as $element_code):

		        $code_id = trim($element_code['code_id']);
		        $code_name = trim($element_code['code_name']);

		        $endCodeid = $group_id.$code_id;

			    DB::table('tbl_common_code_master')->insert([
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
            [ 'group_id' => 'A22', 'group_name' => 'cloudinary 이미지 구분' ],
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
					[ 'code_id' => '000', 'code_name' => '가입대기' ],
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
            'A22' =>
				[
					[ 'code_id' => '000', 'code_name' => '없음' ],
					[ 'code_id' => '010', 'code_name' => '프로필 이미지' ],
					[ 'code_id' => '020', 'code_name' => 'post 이미지' ],
                ],
            'S01' =>
				[
					[ 'code_id' => '000', 'code_name' => '비사용' ],
					[ 'code_id' => '001', 'code_name' => '사용' ],
				],
		];

	}
}

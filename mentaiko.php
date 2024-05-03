<?php
/**
 * Plugin Name: 명란 플러그인
 * Description: 윤매의 숙제하기.
 * Author:      changwoo
 * Author URI:  mailto:changwoo@shoplic.kr
 * Version:     0.0.0
 */

const MENTAIKO_MAIN    = __FILE__;
const MENTAIKO_VERSION = '0.0.0';

// 커스텀 포스트 상수.
const PT_MENTAIKO = 'mentaiko_user';

// 필드 키 상수.
const F_USER_NAME = 'mentaiko_user_name';
const F_EMAIL     = 'mentaiko_email';
const F_REFERER   = 'mentaiko_referer';
const F_AVATAR    = 'mentaiko_avatar';

/**
 * 커스텀 포스트 등록
 * Meta Box 플러그인이 무료 플랜에서는 유저 메타에 대해서는 지원을 하지 않기 때문에,
 * 커스텀 포스트를 마치 회원의 프로필 정보처럼 이용하도록 하겠습니다. 사실 이걸로 로그인 할 것도 아니므로...
 */
if ( ! function_exists( 'mentaiko_register_post_type' ) ) {
	function mentaiko_register_post_type() {
		if ( ! post_type_exists( PT_MENTAIKO ) ) {
			register_post_type( PT_MENTAIKO, [
				'label'  => '명란 회원들',
				'public' => true,
			] );
		}
	}

	add_action( 'init', 'mentaiko_register_post_type' );
}

/**
 * 커스텀 필드 타입 등록.
 */
if ( ! function_exists( 'mentaiko_register_custom_fields' ) ) {
	function mentaiko_register_custom_fields() {
		require_once __DIR__ . '/class-mentaiko-field-referrer.php';
	}

	add_action( 'init', 'mentaiko_register_custom_fields' );
}

/**
 * 커스텀 필드를 정의하는 클래스 이름 변경
 */
if ( ! function_exists( 'mentaiko_field_class' ) ) {
	function mentaiko_field_class( $class, $type ) {
		if ( str_starts_with( $type, 'mentaiko-' ) ) {
			[ $_, $real_type ] = explode( '-', $type, 2 );
			if ( $real_type ) {
				$class = 'Mentaiko_Field_' . ucfirst( $real_type );
			}
		}

		return $class;
	}

	add_filter( 'rwmb_field_class', 'mentaiko_field_class', 10, 2 );
}

/**
 * 명란 회원들에 필요한 커스텀 필드를 등록.
 *
 * 필드:
 * - 이름: 텍스트. 10자 미만.
 * - 이메일: 이메일.
 * - 추천인: 텍스트. 커스텀 UI 형태를 요구함.
 * - 프로필 이미지: 미디어 첨부 이미지.
 * - 필드는 모두 필수입니다.
 */
if ( ! function_exists( 'mentaiko_meta_boxes' ) ) {
	function mentaiko_meta_boxes( array $meta_boxes ): array {
		$field_name = [
			'type' => 'text',
			'name' => '이름',
			'id'   => F_USER_NAME,
			'desc' => '이름. 10자 미만',
		];

		$field_email = [
			'type'     => 'email',
			'name'     => '이메일',
			'id'       => F_EMAIL,
			'desc'     => '이메일 주소',
			'required' => true,
		];

		$field_referrer = [
			'id'       => 'mentaiko_referrer',
			'name'     => '추천인',
			'type'     => 'mentaiko-referrer',
			'required' => true,
		];

		$field_avatar = [
			'type'             => 'image',
			'name'             => '이미지',
			'id'               => F_AVATAR,
			'desc'             => '아바타 이미지',
			'max_file_uploads' => 1,
			'force_delete'     => true, // 이미지를 다시 올리면 기존의 이미지를 지운다.
			'required'         => true,
		];

		$meta_boxes[] = [
			'title'      => '사용자 프로필',
			'id'         => 'mentaoko-user-profile',
			'post_types' => PT_MENTAIKO,
			'context'    => 'normal',
			'fields'     => [
				$field_name,
				$field_email,
				$field_referrer,
				$field_avatar,
			],
			'validation' => [
				'rules' => [
					F_USER_NAME => [
						'required'  => true,
						'maxlength' => 10,
					],
				],
			],
		];

		return $meta_boxes;
	}

	add_filter( 'rwmb_meta_boxes', 'mentaiko_meta_boxes' );
}

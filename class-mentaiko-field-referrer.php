<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Mentaiko_Field_Referrer' ) ) {
	/**
	 * 명란 회원의 추천인 필드 선언
	 *
	 * @link https://docs.metabox.io/creating-new-field-types/
	 */
	class Mentaiko_Field_Referrer extends RWMB_Field {
		public static function admin_enqueue_scripts() {
			wp_enqueue_script(
				'mentaiko-script',
				plugins_url( 'custom-referrer.js', MENTAIKO_MAIN ),
				[ 'jquery' ],
				MENTAIKO_VERSION,
				[
					'in_footer' => true,
					'strategy'  => 'async',
				]
			);
		}

		public static function html( $meta, $field ): string {
			/**
			 * 추천인 소속과 이름 입력
			 */
			ob_start();

			$group = $meta['group'] ?? '';
			$name  = $meta['name'] ?? '';
			?>
            <fieldset name="referrer">
                <label for="<?php echo esc_attr( $field['id'] ); ?>[group]">
                    소속
                </label>
                <select
                    id="<?php echo esc_attr( $field['id'] ); ?>[group]"
                    name="<?php echo esc_attr( $field['field_name'] ); ?>[group]"
                    class="rwmb-select"
                    required="required"
                >
                    <optgroup id="<?php echo esc_attr( $field['id'] ); ?>-optgroup_school" label="학교 지인">
                        <option value="elementary-school" <?php selected( 'elementary-school', $group ); ?>>
                            명란 초등학교
                        </option>
                        <option value="middle-school" <?php selected( 'middle-school', $group ); ?>>
                            명란 중학교
                        </option>
                    </optgroup>
                    <optgroup id="<?php echo esc_attr( $field['id'] ); ?>-optgroup_club" label="동아리">
                        <option value="workout-club" <?php selected( 'workout-club', $group ); ?>>
                            달성 운동 동아리
                        </option>
                        <option value="computer-club" <?php selected( 'computer-club', $group ); ?>>
                            서초 컴퓨터 동아리
                        </option>
                    </optgroup>
                </select>
                <p class="description">
                    추천인 소속이 '학교 지인'인 경우 미리 정해진 이미지로 고정됩니다.
                </p>

                <label for="<?php echo esc_attr( $field['id'] ); ?>[name]">
                    이름
                </label>
                <input
                    id="<?php echo esc_attr( $field['id'] ); ?>[name]"
                    name="<?php echo esc_attr( $field['field_name'] ); ?>[name]"
                    class="rwmb-input"
                    required="required"
                    type="text"
                    value="<?php echo esc_attr( $name ); ?>"
                />
            </fieldset>
			<?php
			return ob_get_clean();
		}
	}
}

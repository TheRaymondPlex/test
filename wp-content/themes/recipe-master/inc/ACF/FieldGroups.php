<?php
/**
 * ACF Field Groups
 *
 * @package RecipeMaster\ACF
 */

namespace RecipeMaster\ACF;

/**
 * FieldGroups class for registering ACF fields
 */
class FieldGroups {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'acf/init', [ $this, 'register_field_groups' ] );
    }

    /**
     * Register ACF field groups
     *
     * @return void
     */
    public function register_field_groups() {
        // Check if ACF function exists
        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        // Recipe Details Field Group
        acf_add_local_field_group( [
            'key'      => 'group_recipe_details',
            'title'    => 'Recipe Details',
            'fields'   => [
                [
                    'key'           => 'field_recipe_prep_time',
                    'label'         => 'Preparation Time',
                    'name'          => 'prep_time_minutes',
                    'type'          => 'number',
                    'instructions'  => 'Enter preparation time in minutes',
                    'required'      => 0,
                    'min'           => 1,
                    'default_value' => 15,
                ],
                [
                    'key'           => 'field_recipe_time',
                    'label'         => 'Cooking Time',
                    'name'          => 'cooking_time',
                    'type'          => 'number',
                    'instructions'  => 'Enter cooking time in minutes',
                    'required'      => 1,
                    'min'           => 1,
                    'default_value' => 30,
                ],
                [
                    'key'           => 'field_recipe_servings',
                    'label'         => 'Servings',
                    'name'          => 'servings',
                    'type'          => 'number',
                    'instructions'  => 'Enter number of servings',
                    'required'      => 1,
                    'min'           => 1,
                    'default_value' => 4,
                ],
                [
                    'key'           => 'field_recipe_calories',
                    'label'         => 'Calories',
                    'name'          => 'calories',
                    'type'          => 'number',
                    'instructions'  => 'Enter calories per serving',
                    'required'      => 0,
                ],
                [
                    'key'          => 'field_recipe_difficulty',
                    'label'        => 'Difficulty Level',
                    'name'         => 'difficulty_level',
                    'type'         => 'select',
                    'instructions' => 'Select difficulty level',
                    'required'     => 1,
                    'choices'      => [
                        'easy'       => 'Easy',
                        'medium'     => 'Medium',
                        'hard'       => 'Hard',
                        'expert'     => 'Expert',
                    ],
                    'default_value' => 'medium',
                ],
                [
                    'key'          => 'field_recipe_cuisine',
                    'label'        => 'Cuisine',
                    'name'         => 'cuisine',
                    'type'         => 'text',
                    'instructions' => 'Enter the cuisine type (e.g., Italian, Mexican, etc.)',
                    'required'     => 0,
                ],
                [
                    'key'          => 'field_recipe_rating',
                    'label'        => 'Rating',
                    'name'         => 'rating',
                    'type'         => 'number',
                    'instructions' => 'Recipe rating (1-5)',
                    'required'     => 0,
                    'min'          => 0,
                    'max'          => 5,
                    'step'         => 0.1,
                ],
                [
                    'key'          => 'field_recipe_review_count',
                    'label'        => 'Review Count',
                    'name'         => 'review_count',
                    'type'         => 'number',
                    'instructions' => 'Number of reviews',
                    'required'     => 0,
                    'min'          => 0,
                    'default_value' => 0,
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'recipe',
                    ],
                ],
            ],
            'position' => 'normal',
        ] );

        // Recipe Ingredients Field Group
        acf_add_local_field_group( [
            'key'      => 'group_recipe_ingredients',
            'title'    => 'Recipe Ingredients',
            'fields'   => [
                [
                    'key'        => 'field_recipe_ingredients',
                    'label'      => 'Ingredients',
                    'name'       => 'ingredients',
                    'type'       => 'repeater',
                    'layout'     => 'table',
                    'button_label' => 'Add Ingredient',
                    'sub_fields' => [
                        [
                            'key'      => 'field_ingredient_name',
                            'label'    => 'Ingredient',
                            'name'     => 'name',
                            'type'     => 'text',
                            'required' => 1,
                        ],
                        [
                            'key'      => 'field_ingredient_amount',
                            'label'    => 'Amount',
                            'name'     => 'amount',
                            'type'     => 'text',
                            'required' => 0,
                        ],
                        [
                            'key'      => 'field_ingredient_unit',
                            'label'    => 'Unit',
                            'name'     => 'unit',
                            'type'     => 'text',
                            'required' => 0,
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'recipe',
                    ],
                ],
            ],
            'position' => 'normal',
        ] );

        // Recipe Instructions Field Group
        acf_add_local_field_group( [
            'key'      => 'group_recipe_instructions',
            'title'    => 'Recipe Instructions',
            'fields'   => [
                [
                    'key'          => 'field_recipe_instructions',
                    'label'        => 'Instructions',
                    'name'         => 'instructions',
                    'type'         => 'repeater',
                    'layout'       => 'block',
                    'button_label' => 'Add Step',
                    'sub_fields'   => [
                        [
                            'key'      => 'field_instruction_step',
                            'label'    => 'Step',
                            'name'     => 'step',
                            'type'     => 'textarea',
                            'required' => 1,
                            'rows'     => 3,
                        ],
                        [
                            'key'      => 'field_instruction_image',
                            'label'    => 'Step Image',
                            'name'     => 'image',
                            'type'     => 'image',
                            'required' => 0,
                            'return_format' => 'id',
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'recipe',
                    ],
                ],
            ],
            'position' => 'normal',
        ] );

        // Recipe Additional Information Field Group
        acf_add_local_field_group( [
            'key'      => 'group_recipe_additional',
            'title'    => 'Additional Information',
            'fields'   => [
                [
                    'key'          => 'field_recipe_tips',
                    'label'        => 'Tips & Notes',
                    'name'         => 'tips',
                    'type'         => 'wysiwyg',
                    'instructions' => 'Enter any tips or additional notes about the recipe',
                    'required'     => 0,
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'recipe',
                    ],
                ],
            ],
            'position' => 'normal',
        ] );
    }
} 
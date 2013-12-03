<?php

    namespace Simplon\Form\Rules;

    use Simplon\Form\Elements\Core\ElementInterface;
    use Simplon\Form\Elements\ElementCheckboxField;
    use Simplon\Form\Rules\Core\RuleCore;

    class RuleRequired extends RuleCore
    {
        protected $_errorMessage = '":label" is required';

        // ######################################

        /**
         * @param ElementInterface $elementInstance
         *
         * @return bool
         */
        public function isValid(ElementInterface $elementInstance)
        {
            // ----------------------------------
            // fields with inmutable values

            if ($elementInstance instanceof ElementCheckboxField)
            {
                if ($elementInstance->hasCheckedOptions() === FALSE)
                {
                    return FALSE;
                }

                return TRUE;
            }

            // ----------------------------------
            // fields with actual mutable values

            $value = $elementInstance->getValue();

            if (empty($value))
            {
                return FALSE;
            }

            return TRUE;
        }
    }
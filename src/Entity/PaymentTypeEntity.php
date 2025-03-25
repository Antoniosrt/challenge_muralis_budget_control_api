<?php
    namespace App\Entity;

    class PaymentTypeEntity
    {
        /**
         * @var int|null
         */
        private ?int $id;

        /**
         * @var string|null
         */
        private ?string $type;

        /**
         * @param int|null $id
         * @param string|null $type
         */
        public function __construct(?int $id, ?string $type)
        {
            $this->id = $id;
            $this->type = $type;
        }


        /**
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * @param int|null $id
         * @return void
         */
        public function setId(?int $id): void
        {
            $this->id = $id;
        }

        /**
         * @return string|null
         */
        public function getType(): ?string
        {
            return $this->type;
        }

        /**
         * @param string|null $type
         * @return void
         */
        public function setType(?string $type): void
        {
            $this->type = $type;
        }


    }
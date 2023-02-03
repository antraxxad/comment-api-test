<?php

namespace App\Comment\Entity;

use App\Comment\Repository\CommentResponseRepository;
use App\User\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentResponseRepository::class)
 * ORM\HasLifecycleCallbacks
 */
class CommentResponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentResponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="responses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->author;
    }

    public function setComment(?Comment $comment): self
    {
        $this->author = $comment;

        return $this;
    }

    /**
     * @return Collection<int, CommentResponse>
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(CommentResponse $response): self
    {
        if (!$this->responses->contains($response)) {
            $this->responses[] = $response;
            $response->setComment($this);
        }

        return $this;
    }

    public function removeResponse(CommentResponse $response): self
    {
        if ($this->commentResponses->removeElement($response)) {
            // set the owning side to null (unless already changed)
            if ($response->getComment() === $this) {
                $response->setComment(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = $this->updatedAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}

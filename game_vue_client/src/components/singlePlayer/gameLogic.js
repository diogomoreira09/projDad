// useGameLogic.js
import { ref } from 'vue';
import { shuffleArray, generateCards } from './gameUtils';

export function useGameLogic(cols, rows) {
  const cards = ref(generateCards(cols, rows)); // Create shuffled cards
  const turns = ref(0);
  const isGameWon = ref(false);
  const pickedCard = ref(null);
  const canFlip = ref(true);

  const flipCard = (card) => {
    if (!canFlip.value || card === pickedCard.value || card.flipped) return;
  
    card.flipped = true; // Flip the card
  
    if (pickedCard.value) {
      canFlip.value = false;
      turns.value++;
  
      if (pickedCard.value.imageId === card.imageId) {
        pickedCard.value = null;
        if (cards.value.every(c => c.flipped)) {
          isGameWon.value = true;
        }
        canFlip.value = true;
      } else {
        setTimeout(() => {
          pickedCard.value.flipped = false;
          card.flipped = false;
          pickedCard.value = null;
          canFlip.value = true;
        }, 1000);
      }
    } else {
      pickedCard.value = card;
    }
};


  const revealHint = () => {
    // Logic for revealing a hint (example: reveal a pair of cards)
    const revealed = cards.value.find(c => !c.flipped);
    if (revealed) {
      revealed.flipped = true;
      setTimeout(() => {
        revealed.flipped = false;
      }, 1000);
    }
  };

  return { cards, turns, isGameWon, flipCard, revealHint };
}
